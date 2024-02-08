<?php

namespace App\Http\Requests\manager;

use Illuminate\Foundation\Http\FormRequest;

class createPimLocaleRequest extends FormRequest
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
            'language.code' => 'required',
            'language.name' => 'required',
            'language.displayClass' => 'required'
        ];
    }
}
