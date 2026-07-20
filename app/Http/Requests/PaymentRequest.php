<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:cash,card,mobile_banking,bank'],
            'transaction_no' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}
