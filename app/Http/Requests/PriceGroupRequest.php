<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('price_groups', 'name')->where('pharmacy_id', currentPharmacyId())->ignore($this->route('price_group'))],
            'status' => ['required', 'boolean'],
        ];
    }
}
