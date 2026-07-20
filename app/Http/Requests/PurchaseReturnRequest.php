<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_batch_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:0'],
        ];
    }
}
