<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:clients,email'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:6', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العميل مطلوب.',
            'name.max' => 'اسم العميل يجب ألا يتجاوز 150 حرفاً.',
            'email.required' => 'البريد الإلكتروني للعميل مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل لعميل آخر.',
            'phone.required' => 'رقم الهاتف مطلوب للتواصل وتسليم الطلبات.',
            'address.required' => 'عنوان العميل مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
        ];
    }
}
