<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', Password::min(6)],
            'status' => ['required', 'boolean'],
            'role' => ['required', Rule::exists('roles', 'name')->where('pharmacy_id', currentPharmacyId())],
        ];
    }
}
