<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg,avif', 'max:10240'],
            'position' => ['required', 'integer', 'in:1,2,3,4'],
            'title' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.mimes' => 'صيغ الصور المسموح بها هي: JPG, JPEG, PNG, WEBP, GIF, SVG, AVIF.',
            'image.max' => 'حجم صورة البانر كبير جداً، الحد الأقصى المسموح به هو 10 ميغابايت.',
            'position.required' => 'يرجى تحديد موضع البانر في السلايدر.',
            'position.in' => 'موضع البانر المحدد غير صالح.',
            'title.max' => 'عنوان البانر يجب ألا يتجاوز 255 حرفاً.',
            'url.max' => 'رابط توجيه البانر طويل جداً.',
        ];
    }
}
