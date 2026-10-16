<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'quantity_delta' => ['required', 'integer', 'not_in:0'],
            'type' => ['required', 'string', 'in:purchase,sale,return,adjustment,damaged'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'يرجى تحديد المنتج المراد تعديل مخزونه.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
            'variant_id.exists' => 'المقاس أو اللون المحدد غير موجود.',
            'branch_id.exists' => 'الفرع أو المستودع المحدد غير موجود.',
            'quantity_delta.required' => 'يرجى إدخال كمية التعديل (سالبة أو موجبة).',
            'quantity_delta.integer' => 'كمية التعديل يجب أن تكون رقماً صحيحاً.',
            'quantity_delta.not_in' => 'كمية التعديل لا يمكن أن تكون صفراً.',
            'type.required' => 'نوع الحركة المخزنية مطلوب.',
            'type.in' => 'نوع الحركة المخزنية غير صالح.',
            'reason.max' => 'سبب التعديل يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
