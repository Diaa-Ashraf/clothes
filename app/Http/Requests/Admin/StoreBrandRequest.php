<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العلامة التجارية مطلوب.',
            'name.string' => 'اسم العلامة التجارية يجب أن يكون نصاً.',
            'name.max' => 'اسم العلامة التجارية لا يجب أن يتجاوز 255 حرفاً.',
            'name.unique' => 'اسم العلامة التجارية مسجل بالفعل مسبقاً.',
            'slug.unique' => 'الرابط المخصص (Slug) مسجل لعلامة تجارية أخرى.',
            'logo.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'logo.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, webp, svg.',
            'logo.max' => 'حجم صورة الشعار لا يجب أن يتجاوز 2 ميجابايت.',
            'website.url' => 'رابط الموقع الإلكتروني يجب أن يكون رابطاً صالحاً (مثال: https://brand.com).',
        ];
    }
}
