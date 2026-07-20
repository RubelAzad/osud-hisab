<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', Rule::exists('categories', 'id')->where('pharmacy_id', currentPharmacyId())],
            'manufacturer_id' => ['required', Rule::exists('manufacturers', 'id')->where('pharmacy_id', currentPharmacyId())],
            'generic_id' => ['required', Rule::exists('generics', 'id')->where('pharmacy_id', currentPharmacyId())],
            'medicine_type_id' => ['required', 'exists:medicine_types,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('medicines', 'barcode')->where('pharmacy_id', currentPharmacyId())->ignore($this->route('medicine'))],
            'medicine_name' => ['required', 'string', 'max:255'],
            'strength' => ['nullable', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'vat' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
        ];
    }
}
