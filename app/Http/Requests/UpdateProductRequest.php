<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'photo' => [
                'nullable',
                File::image()
                ->max(5 * 1024),
            ],
            'name' => [
                'required',
                'max:50',
                Rule::unique('products')->ignore($this->id)
            ],
            'product_code' => [
                'required',
                'max:50',
                Rule::unique('products')->ignore($this->id)
            ],
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'unit_id' => 'required|integer|exists:units,id',
            'stock' => 'required|integer',
        ];
    }
}
