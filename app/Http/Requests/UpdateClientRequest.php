<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client') instanceof \App\Models\Client 
            ? $this->route('client')->id 
            : $this->route('client');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('clients', 'email')->ignore($clientId)],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:6', 'max:32'],
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
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
        ];
    }
}
