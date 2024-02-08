<?php

namespace App\Http\Requests\manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateProfileRequest extends FormRequest
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
        $roleId = $this->route('id');

        return [
//            'profile_name' => 'min:2|unique:App\Models\Profile,name',
            'name' => [
                'required',
                'min:2',
                'max:32',
                Rule::unique('profiles')->ignore($roleId),
            ],
        ];
    }
}
