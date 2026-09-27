<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMagazineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Clean items array from empty rows
        if ($this->has('items') && is_array($this->items)) {
            $cleaned = array_values(array_filter($this->items, function ($item) {
                return !empty($item['product_id']) && isset($item['deal_price']) && is_numeric($item['deal_price']);
            }));
            $this->merge(['items' => $cleaned]);
        }

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'show_countdown' => $this->boolean('show_countdown'),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'branch_note' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'show_countdown' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.original_price' => 'required|numeric|min:0.01',
            'items.*.deal_price' => 'required|numeric|min:0.01',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.badge_text' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان إصدار المجلة مطلوب.',
            'title.max' => 'عنوان المجلة يجب ألا يتجاوز 255 حرفاً.',
            'cover_image.image' => 'الملف يجب أن يكون صورة صالحة.',
            'cover_image.mimes' => 'صيغ الصور المسموحة: jpeg, png, jpg, webp.',
            'cover_image.max' => 'حجم الصورة لا يجب أن يتجاوز 3 ميغابايت.',
            'ends_at.after_or_equal' => 'تاريخ انتهاء المجلة يجب أن يكون بعد أو مساوياً لتاريخ البدء.',
            'items.required' => 'يجب إضافة منتج واحد على الأقل في المجلة.',
            'items.min' => 'يجب إضافة منتج واحد على الأقل في المجلة.',
            'items.*.product_id.required' => 'يرجى تحديد المنتج بشكل صحيح.',
            'items.*.product_id.exists' => 'أحد المنتجات المختارة غير موجود في المتجر.',
            'items.*.deal_price.required' => 'سعر العرض لكل منتج مطلوب.',
            'items.*.deal_price.min' => 'سعر العرض يجب أن يكون أكبر من الصفر.',
        ];
    }
}
