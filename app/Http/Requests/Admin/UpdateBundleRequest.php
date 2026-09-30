<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBundleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('products') && is_array($this->products)) {
            $cleaned = array_values(array_filter($this->products, function ($item) {
                return !empty($item['id']);
            }));
            $this->merge(['products' => $cleaned]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bundle_price' => 'required|numeric|min:0.01',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العرض المجمع مطلوب.',
            'name.max' => 'اسم العرض المجمع يجب ألا يتجاوز 255 حرفاً.',
            'image.image' => 'الملف يجب أن يكون صورة صالحة.',
            'image.mimes' => 'صيغ الصور المسموحة هي: jpeg, png, jpg, webp.',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميغابايت.',
            'bundle_price.required' => 'سعر العرض المجمع مطلوب.',
            'bundle_price.numeric' => 'سعر العرض يجب أن يكون قيمة رقمية.',
            'bundle_price.min' => 'سعر العرض يجب أن يكون أكبر من الصفر.',
            'products.required' => 'يجب اختيار منتجين على الأقل لتكوين عرض مجمع.',
            'products.min' => 'يجب اختيار منتجين على الأقل في العرض المجمع.',
            'products.*.id.required' => 'يرجى تحديد المنتج بشكل صحيح.',
            'products.*.id.exists' => 'أحد المنتجات المختارة غير موجود في قاعدة البيانات.',
            'products.*.quantity.required' => 'كمية المنتج مطلوبة.',
            'products.*.quantity.min' => 'كمية كل منتج يجب ألا تقل عن 1.',
            'ends_at.after_or_equal' => 'تاريخ انتهاء العرض يجب أن يكون مساوياً أو بعد تاريخ البدء.',
        ];
    }
}
