<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:branches,slug',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'working_hours' => 'nullable|array',
            'working_hours.from' => 'nullable|string|max:50',
            'working_hours.to' => 'nullable|string|max:50',
            'working_hours.days' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'google_maps_url' => 'nullable|url|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الفرع مطلوب.',
            'name.max' => 'اسم الفرع يجب ألا يتجاوز 255 حرفاً.',
            'slug.unique' => 'الرابط المخصص (slug) مستخدم بالفعل لفرع آخر.',
            'address.required' => 'عنوان الفرع مطلوب.',
            'address.max' => 'العنوان يجب ألا يتجاوز 500 حرف.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.mimes' => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
            'google_maps_url.url' => 'رابط خرائط جوجل يجب أن يكون رابطاً صحيحاً يبدأ بـ http أو https.',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً.',
        ];
    }
}
