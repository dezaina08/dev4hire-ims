<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StorePurchaseRequest extends FormRequest
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
            'supplier_id' => 'required|integer',
            'purchase_date' => 'required|string',
            'purchase_number' => 'required|string|max:50',
            'purchase_items.*.id' => 'required|integer|exists:products',
            'purchase_items.*.quantity' => 'required|numeric',
            'purchase_items.*.unit_cost' => 'required|numeric',
            'purchase_items.*.total' => 'required|numeric'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'purchase_number' => IdGenerator::generate([
                'table' => 'purchases',
                'field' => 'purchase_number',
                'length' => 9,
                'prefix' => 'PN-'
            ]),
        ]);
    }
}
