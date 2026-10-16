<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        // Align stock_quantity and quantity
        if ($this->has('stock_quantity') && !$this->has('quantity')) {
            $this->merge(['quantity' => $this->input('stock_quantity')]);
        } elseif ($this->has('quantity') && !$this->has('stock_quantity')) {
            $this->merge(['stock_quantity' => $this->input('quantity')]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:men,women,kids,unisex',
            'season' => 'nullable|string|in:summer,winter,spring,autumn,all_seasons',
            'material' => 'nullable|string|max:100',
            'fit' => 'nullable|string|in:slim,regular,oversized,loose,relaxed',
            'description' => 'nullable|string',
            'keywords' => 'nullable',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'slug' => 'nullable|string|max:255',
            'featured' => 'nullable',
            'is_active' => 'nullable',
            'is_weighted' => 'nullable',
            'unit_type' => 'nullable|string|in:kg,piece,gram,liter',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attribute_values' => 'nullable|array',
        ];
    }

    /**
     * Custom Arabic error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'يرجى اختيار القسم أو التصنيف للمنتج.',
            'category_id.exists' => 'التصنيف المختار غير موجود.',
            'name.required' => 'يرجى إدخال اسم المنتج.',
            'name.max' => 'اسم المنتج طويل جداً (الحد الأقصى 255 حرف).',
            'price.required' => 'يرجى تحديد سعر المنتج.',
            'price.numeric' => 'يجب أن يكون السعر رقماً صحيحاً أو عشرياً.',
            'price.min' => 'لا يمكن أن يكون السعر أقل من صفر.',
            'discount_price.numeric' => 'يجب أن يكون سعر الخصم رقماً.',
            'discount_price.lt' => 'يجب أن يكون سعر الخصم أقل من السعر الأساسي للمنتج.',
            'stock_quantity.required' => 'يرجى تحديد كمية المخزون المتوفرة.',
            'stock_quantity.integer' => 'كمية المخزون يجب أن تكون رقماً صحيحاً.',
            'stock_quantity.min' => 'كمية المخزون لا يمكن أن تكون سالبة.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'image.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}
