<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
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
            'branch_id' => ['nullable', 'exists:branches,id'],
            'position' => ['required', 'string', 'max:100'],
            'cv_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'], // Max 10MB
            'message' => ['nullable', 'string', 'max:1500'],
        ];
    }

    /**
     * Custom Arabic error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال اسمك بالكامل.',
            'name.min' => 'الاسم يجب أن يتكون من 3 أحرف على الأقل.',
            'phone.required' => 'يرجى إدخال رقم الهاتف للتواصل معك.',
            'phone.regex' => 'يرجى إدخال رقم هاتف مصري صحيح (مثال: 01012345678).',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'branch_id.exists' => 'الفرع المختار غير متاح حالياً.',
            'position.required' => 'يرجى اختيار أو تحديد الوظيفة المتقدم لها.',
            'cv_file.required' => 'يرجى رفع السيرة الذاتية أو صورة من بياناتك الشخصية (CV).',
            'cv_file.mimes' => 'يجب أن يكون الملف بصيغة PDF أو Word أو صورة (png, jpg).',
            'cv_file.max' => 'حجم الملف لا يجب أن يتجاوز 10 ميجابايت.',
            'message.max' => 'الرسالة لا يجب أن تتجاوز 1500 حرف.',
        ];
    }
}
