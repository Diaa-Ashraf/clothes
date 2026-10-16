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
            'position' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'bg_color' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.mimes' => 'صيغ الصور المسموح بها هي: JPG, JPEG, PNG, WEBP, GIF, SVG, AVIF.',
            'image.max' => 'حجم صورة البانر كبير جداً، الحد الأقصى المسموح به هو 10 ميغابايت.',
            'position.required' => 'يرجى تحديد موضع العرض.',
            'title.required' => 'يرجى إدخال عنوان الكولكشن أو البانر.',
            'title.max' => 'عنوان البانر يجب ألا يتجاوز 255 حرفاً.',
            'subtitle.max' => 'الوسم أو العنوان الفرعي يجب ألا يتجاوز 255 حرفاً.',
            'url.max' => 'رابط التوجيه طويل جداً.',
            'button_text.max' => 'نص زر التوجيه يجب ألا يتجاوز 100 حرف.',
        ];
    }
}
