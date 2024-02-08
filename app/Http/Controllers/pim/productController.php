<?php

namespace App\Http\Controllers\pim;

use App\Http\Controllers\Controller;
use App\Http\Requests\pim\createProductRequest;
use App\Http\Requests\pim\createRelationRequest;
use App\Http\Requests\pim\updateRelationRequest;
use App\Models\pim\Product;
use App\Models\pim\Product_relation;
use App\Models\pim\Products_asset;
use App\Models\pim\Relation_type;
use App\Models\pim\specifications_products_values;
use App\Models\pim\specifications_products_values_translations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{
    public function index(): Collection|null
    {
        return Product::with('assets')->get();
    }

    /**
     * This finds and returns a singular product with the respective assets by the given ID
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function show($id): Model|Collection|Builder|array|null
    {
        return Product::with('assets')->find($id);
    }

    /**
     * This function deletes a product by the given ID and additionally deletes any assets that the product may have contained
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $product = Product::find($id);
        if ($product) {
            Storage::deleteDirectory('public/productFiles/'.$id);
            $product->delete();

            return response()->json(['message' => 'Succesfully deleted product.']);
        }

        return response()->json(['message' =>'Failed to delete product...'], 500);
    }

    public function create(createProductRequest $request): void
    {
        $product = new Product();
        $product->id = $request->input('id');

        $product->save();
    }

    public function save(createProductRequest $request, $id): void
    {
        $product = Product::find($id);

        $product->id = $request->input('id');

        $product->save();
    }

    /**
     * This function uploads assets to the Laravel Eloquent storage system.
     * Additionally, it saves data such as the product ID, file path, file name and file type in the respective table
     * @param Request $request
     * @return void
     */
    public function assetUploader(Request $request): void
    {
        $uploadedFile = $request->file('file');
        $directory = 'productFiles/'.$request->input('id');
        $path = $uploadedFile->store($directory, 'public');
        $product_asset = new Products_asset();
        $product_asset->product_id = $request->input('id');
        $product_asset->file_path = $path;
        $product_asset->file_name = $uploadedFile->getClientOriginalName();
        $product_asset->file_type = $uploadedFile->getClientOriginalExtension();
        $product_asset->save();
    }

    public function deleteAllAssets(Request $request): void
    {
        $id = $request->input('id');

        Storage::deleteDirectory('public/productFiles/'.$id);
        DB::table('products_assets')->where('product_id', $id)->delete();
    }

    public function deleteAsset(Request $request): void
    {
        $file_path = $request->input('asset.file_path');
        $id = $request->input('asset.id');
        Storage::delete('public/'.$file_path);
        DB::table('products_assets')->where('products_assets.id', $id)->delete();
    }

    public function addSpecification($id, Request $request): void
    {
        $productSpecification = new specifications_products_values();

        $productSpecification->product_id = $id;
        $productSpecification->specification_id = $request->specification['id'];

        $productSpecification->save();

        $translation = new specifications_products_values_translations();

        $translation->locale = 'nl';
        $translation->value_id = $productSpecification->id;
        $translation->value = $request->value;

        $translation->save();
    }

    public function removeSpecification($id): void
    {
        $productSpecification = specifications_products_values::find($id);


        $productSpecification->delete();
    }

    public function removeAllSpecifications($id): void
    {
        DB::table('specifications_products_values')->where('product_id', $id)->delete();
    }

    /**
     * This gets the specifications of a product together with the translations and the translations of the specification
     * @param $id
     * @param Request $request
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function getProductSpecifications($id, Request $request): Collection|\Illuminate\Support\Collection|array
    {
        $locale = $request->input('locale'); // or you can get it from the request if needed

        $results = specifications_products_values::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            },
            'specifications.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }
        ])
            ->where('product_id', $id)
            ->get();

        $filteredResults = $results->filter(function ($result) use ($locale) {
            // Check if there is at least one translation in the specified locale
            return $result->specifications->translations->where('locale', $locale)->isNotEmpty();
        });

        return $filteredResults;
    }

    /**
     * This updates the relevant specification per the chosen locale for the given product
     * @param $id
     * @param Request $request
     * @return void
     */
    public function updateSpecification($id, Request $request): void
    {
        $locale = $request->input('locale');
        $translationExists = specifications_products_values_translations::where('value_id', $id)->where('locale', $locale)->first();

        if ($translationExists) {
            $translation = specifications_products_values_translations::where('value_id', $id)->where('locale', $locale)->first();
            $translation->value = $request->input('specification.translations.0.value');
        } else {
            $translation = new specifications_products_values_translations();
            $translation->value_id = $request->input('specification.id');
            $translation->value = $request->input('specification.translations.0.value');
            $translation->locale = $request->input('locale');

        }
        $translation->save();
    }

    public function getRelationTypes()
    {
        return Relation_type::all();
    }

    public function getRelations($id)
    {
        return Product_relation::all()->where('parent_id', $id);
    }

    public function saveRelation($id, updateRelationRequest $request): void
    {
        $child = $request->input('relations.child');
        $type = $request->input('relations.type');

        $relation = Product_relation::where('parent_id', $id)->where('child_id', $child)->first();

        $relation->child_id = $child;
        $relation->type = $type;

        $relation->save();
    }

    public function createRelation($id, createRelationRequest $request): void
    {
        $child = $request->input('child_id');
        $type = $request->input('type');

        $relation = new Product_relation();

        $relation->parent_id = $id;
        $relation->child_id = $child;
        $relation->type = $type;

        $relation->save();
    }

    public function deleteRelation($id, Request $request): void
    {
        $child = $request->input('relation.child_id');
        $relation = Product_relation::where('parent_id', $id)->where('child_id', $child)->first();
        $relation->parent()->disassociate();
        $relation->child()->disassociate();
        $relation->save();
        $relation->delete();
    }

    public function removeAllRelations($id): void
    {
        DB::table('product_relations')->where('parent_id', $id)->delete();
    }
}
