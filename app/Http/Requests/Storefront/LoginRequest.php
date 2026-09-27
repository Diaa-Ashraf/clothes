<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'يرجى إدخال رقم الهاتف أو البريد الإلكتروني.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ];
    }
}
