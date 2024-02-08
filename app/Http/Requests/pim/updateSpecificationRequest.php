<?php

namespace App\Http\Requests\pim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateSpecificationRequest extends FormRequest
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
        $specificationId = $this->input('translation_id');

        return [
            'specification.name' => [
                'required',
                'min:2',
                'max:32',
                Rule::unique('specifications_translations', 'name')->ignore($specificationId),
            ],
            'specification.inputType' => [
                'required',
                'exists:specification_inputTypes,id'
            ],
            'specification.description' => [
                'max:255'
            ]
        ];
    }
}
