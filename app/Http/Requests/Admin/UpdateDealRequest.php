<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:99'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'deal_price' => ['required', 'numeric', 'min:0'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'يرجى اختيار المنتج للعرض الخاص.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
            'discount_percent.required' => 'نسبة الخصم مطلوبة.',
            'discount_percent.min' => 'نسبة الخصم يجب ألا تقل عن 1%.',
            'discount_percent.max' => 'نسبة الخصم يجب ألا تتجاوز 99%.',
            'original_price.required' => 'السعر الأصلي مطلوب.',
            'deal_price.required' => 'سعر العرض الترويجي مطلوب.',
            'ends_at.after_or_equal' => 'تاريخ انتهاء العرض يجب أن يكون بعد أو مساوياً لتاريخ البدء.',
        ];
    }
}
