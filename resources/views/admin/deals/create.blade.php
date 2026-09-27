@extends('layouts.admin')

@section('title', __('إضافة تخفيض حصري'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('إضافة تخفيض حصري (Flash Deal)') }}</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('تحديد خصم مئوي وموعد انتهاء لمنتج معين مع شارة عرض جذابة') }}</p>
        </div>
        <a href="{{ route('deals.index') }}" class="px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            &larr; {{ __('العودة للعروض') }}
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold">
            <div class="mb-1">{{ __('يرجى تصحيح الأخطاء التالية:') }}</div>
            <ul class="list-disc list-inside space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs">
        <form action="{{ route('deals.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product_id" class="block text-xs font-bold text-gray-900 mb-2">{{ __('اختر المنتج') }} <span class="text-red-500">*</span></label>
                    <select name="product_id" id="product_id" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" required onchange="updatePrices()">
                        <option value="">-- {{ __('اختر المنتج المعني') }} --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - {{ number_format($product->price, 2) }} ج.م
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="discount_percent" class="block text-xs font-bold text-gray-900 mb-2">{{ __('نسبة الخصم المئوية') }} (%) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="discount_percent" id="discount_percent" 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-black transition-all" min="1" max="99" 
                               value="{{ old('discount_percent', 10) }}" 
                               required oninput="calculateDealPrice()">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-400">%</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 rounded-2xl bg-amber-50/50 border border-amber-100">
                <div>
                    <label for="original_price" class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('السعر الأصلي للمنتج') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="original_price" id="original_price" 
                               class="w-full pl-12 pr-4 py-2 rounded-xl border border-orange-200 bg-white text-gray-900 text-xs font-black focus:outline-none" min="0" 
                               value="{{ old('original_price') }}" 
                               required oninput="calculateDealPrice()">
                        <span class="absolute left-3 top-2 text-xs font-bold text-gray-400">ج.م</span>
                    </div>
                </div>

                <div>
                    <label for="deal_price" class="block text-xs font-bold text-emerald-900 mb-1.5">{{ __('سعر العرض النهائي بعد الخصم') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="deal_price" id="deal_price" 
                               class="w-full pl-12 pr-4 py-2 rounded-xl border border-emerald-200 bg-white text-emerald-700 text-xs font-black focus:outline-none" min="0" 
                               value="{{ old('deal_price') }}" required>
                        <span class="absolute left-3 top-2 text-xs text-emerald-600 font-bold">ج.م</span>
                    </div>
                    <small class="text-emerald-700 text-[11px] font-medium mt-1 block">يُحسب تلقائياً وفق نسبة الخصم المحددة أعلاه</small>
                </div>
            </div>

            <div>
                <label for="badge_text" class="block text-xs font-bold text-gray-900 mb-2">{{ __('نص الشارة الترويجية (Badge Text)') }}</label>
                <input type="text" name="badge_text" id="badge_text" 
                       class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" 
                       value="{{ old('badge_text', 'عرض خاص') }}" 
                       placeholder="مثال: خصم خاطف 50% أو عرض الجمعة">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="starts_at" class="block text-xs font-bold text-gray-900 mb-2">{{ __('تاريخ وبدء العرض') }}</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" 
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('starts_at') }}">
                    <small class="text-gray-400 text-[11px] mt-1 block">اتركه فارغاً ليبدأ العرض فوراً</small>
                </div>

                <div>
                    <label for="ends_at" class="block text-xs font-bold text-gray-900 mb-2">{{ __('تاريخ انتهاء العرض (عداد تنازلي)') }}</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" 
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('ends_at') }}">
                    <small class="text-gray-400 text-[11px] mt-1 block">اتركه فارغاً إذا كان العرض مستمراً دون وقت محدد</small>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <label for="sort_order" class="block text-xs font-bold text-gray-900 mb-2">{{ __('ترتيب الظهور') }}</label>
                    <input type="number" name="sort_order" id="sort_order" 
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('sort_order', 0) }}" min="0">
                </div>

                <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100 mt-2">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-orange-200" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-gray-900">✓ تفعيل العرض فوراً بالمتجر</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
                <a href="{{ route('deals.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('حفظ ونشر العرض') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updatePrices() {
    var select = document.getElementById('product_id');
    var selectedOption = select.options[select.selectedIndex];
    var price = selectedOption.getAttribute('data-price');
    
    if (price) {
        document.getElementById('original_price').value = parseFloat(price).toFixed(2);
        calculateDealPrice();
    }
}

function calculateDealPrice() {
    var originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
    var discountPercent = parseInt(document.getElementById('discount_percent').value) || 0;
    
    var dealPrice = originalPrice - (originalPrice * discountPercent / 100);
    document.getElementById('deal_price').value = dealPrice.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('original_price').value) {
        calculateDealPrice();
    }
});
</script>
@endsection
