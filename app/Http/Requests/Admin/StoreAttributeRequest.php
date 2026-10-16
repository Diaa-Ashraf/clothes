<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:attributes,code'],
            'type' => ['required', 'string', 'in:button,color,select,text'],
            'is_filterable' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخاصية مطلوب (مثال: المقاس، اللون، الخامة).',
            'code.required' => 'الرمز البرمجي للخاصية مطلوب (مثال: size, color).',
            'code.unique' => 'الرمز البرمجي مستخدم بالفعل لخاصية أخرى.',
            'type.required' => 'نوع عرض الخاصية مطلوب.',
            'type.in' => 'نوع العرض غير صالح.',
        ];
    }
}
