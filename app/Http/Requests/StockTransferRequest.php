<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_location_id' => ['required', 'different:to_location_id', Rule::exists('locations', 'id')->where('pharmacy_id', currentPharmacyId())],
            'to_location_id' => ['required', Rule::exists('locations', 'id')->where('pharmacy_id', currentPharmacyId())],
            'transfer_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', Rule::exists('medicines', 'id')->where('pharmacy_id', currentPharmacyId())],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }
}
