<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', Rule::exists('suppliers', 'id')->where('pharmacy_id', currentPharmacyId())],
            'location_id' => ['sometimes', Rule::exists('locations', 'id')->where('pharmacy_id', currentPharmacyId())],
            'purchase_date' => ['required', 'date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'vat' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', Rule::exists('medicines', 'id')->where('pharmacy_id', currentPharmacyId())],
            'items.*.batch_no' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.purchase_price' => ['required', 'numeric', 'min:0'],
            'items.*.sale_price' => ['required', 'numeric', 'min:0'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.manufacture_date' => ['nullable', 'date'],
        ];
    }
}
