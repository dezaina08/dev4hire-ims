<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|max:50|unique:units',
            'product_code' => 'required|max:50|unique:products',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'unit_id' => 'required|integer|exists:units,id',
            'stock' => 'required|integer',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'product_code' => IdGenerator::generate([
                'table' => 'products',
                'field' => 'product_code',
                'length' => 8,
                'prefix' => 'PC-'
            ]),
        ]);
    }
}
