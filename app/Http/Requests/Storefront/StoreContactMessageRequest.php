<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^(01[0125][0-9]{8}|\+201[0125][0-9]{8})$/'],
            'email' => ['nullable', 'email', 'max:100'],
            'subject' => ['required', 'string', 'min:3', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * Custom Arabic error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى كتابة اسمك الكريم.',
            'name.min' => 'الاسم يجب ألا يقل عن 3 أحرف.',
            'phone.required' => 'يرجى كتابة رقم الهاتف للتواصل معك.',
            'phone.regex' => 'يرجى كتابة رقم هاتف مصري صحيح (مثال: 01012345678).',
            'email.email' => 'البريد الإلكتروني المدخل غير صحيح.',
            'subject.required' => 'يرجى تحديد موضوع الرسالة.',
            'message.required' => 'يرجى كتابة تفاصيل استفسارك أو رسالتك.',
            'message.min' => 'الرسالة قصيرة جداً، يرجى كتابة تفاصيل واضحة (10 أحرف على الأقل).',
            'message.max' => 'الرسالة لا يجب أن تتجاوز 2000 حرف.',
        ];
    }
}
