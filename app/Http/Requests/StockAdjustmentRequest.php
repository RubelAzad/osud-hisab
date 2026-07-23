<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medicine_batch_id' => ['required', Rule::exists('medicine_batches', 'id')->where('pharmacy_id', currentPharmacyId())],
            'type' => ['required', Rule::in(['increase', 'decrease'])],
            'qty' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ];
    }
}
