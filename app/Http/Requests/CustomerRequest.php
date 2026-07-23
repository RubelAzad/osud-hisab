<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'opening_balance' => [$this->isMethod('post') ? 'required' : 'sometimes', 'numeric'],
            'customer_group_id' => ['nullable', Rule::exists('customer_groups', 'id')->where('pharmacy_id', currentPharmacyId())],
        ];
    }
}
