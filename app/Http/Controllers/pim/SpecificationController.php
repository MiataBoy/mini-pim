<?php

namespace App\Http\Controllers\pim;

use App\Http\Controllers\Controller;
use App\Http\Requests\pim\createSpecificationRequest;
use App\Http\Requests\pim\updateSpecificationRequest;
use App\Models\pim\Specification;
use App\Models\pim\Specifications_translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * Retrieves the specifications together with their respective translations and the available inputType
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        if ($request->query('enabled')) {
            Specification::with('translations', 'inputType')->where('enabled', true);
        }

        return Specification::with('translations', 'inputType')->get();
    }

    /**
     * Gets the translations of a specification in the given locale
     * @param $id
     * @param Request $request
     * @return object|null
     */
    public function getTranslation($id, Request $request): object|null
    {
        $locale = $request->input('locale');

        $specification = Specification::with('inputType')
            ->where('id', $id)
            ->first();

        if ($specification) {
            $translations = $specification->translations()
                ->where('locale', $locale)
                ->get()
                ->toArray();

            $specification->translations = $translations;
        }

        return $specification;
    }

    public function delete($id) {
        $specification = Specification::find($id);

        $specification->translations()->delete();
        $specification->delete();
    }

    public function create(createSpecificationRequest $request) {
        $name = $request->input('specification.name');
        $inputType = $request->input('specification.inputType');
        $locale = $request->input('locale');

        $specification = Specification::create([
            'inputType_id' => $inputType,
        ]);

        // Step 2: Insert into specifications_translations table
        Specifications_translation::create([
            'specification_id' => $specification->id,
            'locale' => $locale,
            'name' => $name,
            'description' => $request->input('specification.description') ?: null,
        ]);
    }

    public function update($id, updateSpecificationRequest $request)
    {
        $name = $request->input('specification.name');
        $inputType = $request->input('specification.inputType');
        $description = $request->input('specification.description');
        $locale = $request->input('locale');
        $trans_id = $request->input('specification.translation_id');

        if (!Specifications_translation::where('locale', $locale)->where('specification_id', $id)->exists()) {
            $translation = new Specifications_translation();
            $translation->specification_id = $id;
        } else {
            $translation = Specifications_translation::where('locale', $locale)->find($trans_id);
        }

        $specification = Specification::find($id);

        $specification->inputType_id = $inputType;

        $translation->locale = $locale;
        $translation->name = $name;
        $translation->description = $description;

        $specification->save();
        $translation->save();
    }
}
