<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('tax_rates', 'name')->where('pharmacy_id', currentPharmacyId())->ignore($this->route('tax_rate'))],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'boolean'],
        ];
    }
}
