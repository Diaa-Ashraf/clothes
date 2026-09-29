<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:150',
            'phone' => ['nullable', 'string', 'unique:users,phone'],
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string|max:300',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'يرجى كتابة الاسم الكريم.',
            'name.min' => 'الاسم يجب ألا يقل عن 3 أحرف.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 150 حرفاً.',
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.regex' => 'يرجى إدخال رقم هاتف مصري صحيح (مثال: 01012345678).',
            'phone.unique' => 'رقم الهاتف هذا مسجل لدينا بالفعل، يمكنك تسجيل الدخول به.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني هذا مستخدم بالفعل.',
            'password.required' => 'يرجى كتابة كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 خانات على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'address.max' => 'العنوان لا يجب أن يتجاوز 300 حرف.',
        ];
    }
}
