<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => [
                'required',
                'max:50',
                Rule::unique('users')->ignore($this->id)
            ],
            'username' => [
                'required',
                'max:50',
                Rule::unique('users')->ignore($this->id)
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->id)
            ],
            'role' => 'required|numeric',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }
}
