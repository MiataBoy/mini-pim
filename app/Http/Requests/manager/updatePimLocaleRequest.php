<?php

namespace App\Http\Requests\manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updatePimLocaleRequest extends FormRequest
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
        $localeId = $this->route('id');

        return [
            'language.name' => [
                'required',
                'min:2',
                'max:32',
                Rule::unique('pim_locales', 'name')->ignore($localeId),
            ],
            'language.code' => [
                'required',
                'min:2',
                'max:8',
                Rule::unique('pim_locales', 'code')->ignore($localeId),
            ],
            'language.displayClass' => [
                'required',
                'min:2',
                'max:8',
                Rule::unique('pim_locales', 'displayClass')->ignore($localeId),
            ]
        ];
    }
}
