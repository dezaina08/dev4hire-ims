<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'name' => 'required|max:50|unique:suppliers',
            'email' => 'nullable|email|max:50|unique:suppliers',
            'phone_number' => 'nullable|max:50',
            'address' => 'nullable|max:100',
            'shop_name' => 'nullable|max:50',
            'bank_name' => 'nullable|max:50',
            'account_holder' => 'nullable|max:50',
            'account_number' => 'nullable|max:50',
        ];
    }
}
