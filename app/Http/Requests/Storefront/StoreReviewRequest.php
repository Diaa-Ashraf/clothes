<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'معرّف المنتج مطلوب.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
            'rating.required' => 'يرجى تحديد التقييم بالنجوم.',
            'rating.min' => 'الحد الأدنى للتقييم هو نجمة واحدة.',
            'rating.max' => 'الحد الأقصى للتقييم هو 5 نجوم.',
            'comment.max' => 'يجب ألا يتجاوز التعليق 1000 حرف.',
        ];
    }
}
