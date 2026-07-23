<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'applies_to' => ['required', Rule::in(['all', 'category', 'medicine'])],
            'category_id' => ['nullable', 'required_if:applies_to,category', Rule::exists('categories', 'id')->where('pharmacy_id', currentPharmacyId())],
            'medicine_id' => ['nullable', 'required_if:applies_to,medicine', Rule::exists('medicines', 'id')->where('pharmacy_id', currentPharmacyId())],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', 'boolean'],
        ];
    }
}
