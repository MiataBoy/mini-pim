<?php

namespace App\Http\Requests\pim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateRelationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $relationId = $this->input('relations.id');

        return [
            'relations.child_id' => [
                'unique:App\Models\pim\Product_relation',
                'child_id|required|exists:products,id',
                Rule::unique('product_relations', 'id')->ignore($relationId)
            ],
            'relations.type' => 'required|exists:relation_types,id'
        ];
    }
}
