<?php

namespace App\Http\Requests\manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateUserRequest extends FormRequest
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
        $userId = $this->route('id'); // Assuming 'id' is the route parameter name

        return [
            'name' => 'required',
            'username' => [
                'required',
                'max:16',
                Rule::unique('users')->ignore($userId),
            ],
            'company' => 'required',
            'profile_id' => 'required|numeric|exists:profiles,id',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [
                'nullable',
                'required_if:password_confirmation,!=,null',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => 'required_with:password|string|min:8',
        ];
    }

}
