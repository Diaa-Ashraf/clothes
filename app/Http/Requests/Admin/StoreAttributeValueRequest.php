<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attribute_id' => ['required', 'exists:attributes,id'],
            'value' => ['required', 'string', 'max:100'],
            'color_code' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'الخاصية التابعة مطلوبة.',
            'attribute_id.exists' => 'الخاصية المحددة غير موجودة.',
            'value.required' => 'قيمة الخاصية مطلوبة (مثال: XL أو أسود).',
            'color_code.regex' => 'كود اللون يجب أن يكون بصيغة Hex صالحة (مثال: #000000 أو #FFF).',
        ];
    }
}
