<?php

namespace App\Http\Requests\pim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class createRelationRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'child_id' => [
                'required',
                'exists:products,id',
                Rule::unique('product_relations')->where('parent_id', $id)
            ],
            'type' => 'required|exists:relation_types,id',
        ];
    }
}
