<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand') instanceof \App\Models\Brand 
            ? $this->route('brand')->id 
            : $this->route('brand');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($brandId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brandId)],
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
            'website.url' => 'رابط الموقع الإلكتروني يجب أن يكون رابطاً صالحاً.',
        ];
    }
}
