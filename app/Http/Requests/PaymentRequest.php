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
            'payment_method' => ['required', 'string', 'in:cash,card,mobile_banking,bank,cheque'],
            'transaction_no' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'cheque_no' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
            'bank_name' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
            'cheque_date' => ['required_if:payment_method,cheque', 'nullable', 'date'],
            'due_date' => ['required_if:payment_method,cheque', 'nullable', 'date'],
        ];
    }
}
