@extends('layouts.admin')

@section('title', 'تعديل العرض الخاص')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.deals.index') }}" class="hover:text-[#2B2118] transition-colors">العروض الخاصة</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">تعديل العرض #{{ $deal->id }}</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">تعديل العرض الترويجي</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تعديل نسبة الخصم وسعر العرض والشارة للمنتج: {{ $deal->product?->name }}</p>
        </div>
        <a href="{{ route('admin.deals.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للعروض</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50/50 border border-[#A9552F]/30 text-[#A9552F] text-xs space-y-1">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm p-6 sm:p-8" x-data="dealForm()">
        <form action="{{ route('admin.deals.update', $deal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Select -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="product_id" class="block text-xs font-bold text-[#2B2118]">
                        اختر قطعة الأزياء المعنية بالخصم <span class="text-[#A9552F]">*</span>
                    </label>
                    <select name="product_id" id="product_id" required x-model="productId" @change="onProductChange($event)"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    {{ old('product_id', $deal->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} — السعر الأساسي: {{ number_format($product->price, 2) }} ج.م
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Original Price -->
                <div class="space-y-1.5">
                    <label for="original_price" class="block text-xs font-bold text-[#2B2118]">
                        السعر الأصلي (ج.م) <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" name="original_price" id="original_price" required
                           x-model="originalPrice" @input="recalculateByPercent()"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-[#FDFBF7] text-sm text-[#2B2118] transition-all dir-ltr text-right">
                </div>

                <!-- Discount Percent -->
                <div class="space-y-1.5">
                    <label for="discount_percent" class="block text-xs font-bold text-[#2B2118]">
                        نسبة الخصم المئوية (%) <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="number" min="1" max="99" name="discount_percent" id="discount_percent" required
                           x-model="discountPercent" @input="recalculateByPercent()"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all dir-ltr text-right">
                </div>

                <!-- Deal Price -->
                <div class="space-y-1.5">
                    <label for="deal_price" class="block text-xs font-bold text-[#2B2118]">
                        السعر بعد الخصم (سعر العرض الحصري) <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" name="deal_price" id="deal_price" required
                           x-model="dealPrice" @input="recalculateByPrice()"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm font-bold text-[#A9552F] transition-all dir-ltr text-right">
                </div>

                <!-- Badge Text -->
                <div class="space-y-1.5">
                    <label for="badge_text" class="block text-xs font-bold text-[#2B2118]">
                        نص الشارة الترويجية (Badge)
                    </label>
                    <input type="text" name="badge_text" id="badge_text" value="{{ old('badge_text', $deal->badge_text) }}"
                           placeholder="مثال: خصم الصيف، عرض لفترة محدودة، صفقة حصرية"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all">
                </div>

                <!-- Starts At -->
                <div class="space-y-1.5">
                    <label for="starts_at" class="block text-xs font-bold text-[#2B2118]">
                        تاريخ ووقت بدء العرض
                    </label>
                    <input type="datetime-local" name="starts_at" id="starts_at" 
                           value="{{ old('starts_at', $deal->starts_at ? $deal->starts_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                </div>

                <!-- Ends At -->
                <div class="space-y-1.5">
                    <label for="ends_at" class="block text-xs font-bold text-[#2B2118]">
                        تاريخ ووقت انتهاء العرض
                    </label>
                    <input type="datetime-local" name="ends_at" id="ends_at" 
                           value="{{ old('ends_at', $deal->ends_at ? $deal->ends_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                </div>

                <!-- Sort Order -->
                <div class="space-y-1.5">
                    <label for="sort_order" class="block text-xs font-bold text-[#2B2118]">
                        ترتيب الظهور في الواجهة
                    </label>
                    <input type="number" min="0" name="sort_order" id="sort_order" value="{{ old('sort_order', $deal->sort_order) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all dir-ltr text-right">
                </div>
            </div>

            <!-- Active Status Toggle -->
            <div class="pt-2 border-t border-[#EADFC8]">
                <label class="relative inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $deal->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-[#EADFC8] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#382416]"></div>
                    <span class="text-xs font-bold text-[#2B2118]">تفعيل العرض</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EADFC8]">
                <a href="{{ route('admin.deals.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                    تحديث العرض
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function dealForm() {
    return {
        productId: '{{ old('product_id', $deal->product_id) }}',
        originalPrice: {{ old('original_price', $deal->original_price) }},
        discountPercent: {{ old('discount_percent', $deal->discount_percent) }},
        dealPrice: {{ old('deal_price', $deal->deal_price) }},
        onProductChange(event) {
            const selected = event.target.options[event.target.selectedIndex];
            if (selected && selected.dataset.price) {
                this.originalPrice = parseFloat(selected.dataset.price);
                this.recalculateByPercent();
            }
        },
        recalculateByPercent() {
            if (this.originalPrice > 0 && this.discountPercent > 0) {
                const discountAmount = (this.originalPrice * this.discountPercent) / 100;
                this.dealPrice = (this.originalPrice - discountAmount).toFixed(2);
            }
        },
        recalculateByPrice() {
            if (this.originalPrice > 0 && this.dealPrice > 0) {
                const diff = this.originalPrice - this.dealPrice;
                if (diff > 0) {
                    this.discountPercent = Math.round((diff / this.originalPrice) * 100);
                }
            }
        }
    };
}
</script>
@endsection
