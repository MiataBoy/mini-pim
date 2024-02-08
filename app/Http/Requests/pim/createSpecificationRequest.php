<?php

namespace App\Http\Requests\pim;

use Illuminate\Foundation\Http\FormRequest;

class createSpecificationRequest extends FormRequest
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
        return [
            'specification.name' => [
                'required',
                'min:2',
                'max:32',
                'Unique:App\Models\pim\specifications_translation,name',
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
