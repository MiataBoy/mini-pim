<?php

namespace App\Http\Requests\manager;

use Illuminate\Foundation\Http\FormRequest;

class createProfileRequest extends FormRequest
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
            'name' => 'required|min:2|unique:App\Models\manager\Profile,name|max:32'
        ];
    }
}
