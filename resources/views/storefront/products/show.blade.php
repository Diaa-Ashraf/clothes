{{-- resources/views/storefront/products/show.blade.php --}}
@extends('layouts.storefront')

@section('title', $product->name . ' | ألبان وسوبرماركت التوحيد')
@section('meta_description', Str::limit(strip_tags($product->description), 160))

@php
    $finalPrice = $product->final_price ?? $product->discount_price ?? $product->price;
    $hasDiscount = ($product->discount_price && $product->discount_price < $product->price);
    $discountPercent = $hasDiscount && $product->price > 0 ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0;
    $isInStock = ($product->is_available && ($product->stock_quantity > 0 || $product->quantity > 0));
    $isWeighted = (bool)$product->is_weighted;
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $cleanWhatsApp = preg_replace('/[^0-9]/', '', $storeWhatsApp);
@endphp

@push('styles')
<!-- Schema.org JSON-LD Structured Data for Supermarket Product -->
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": "{{ $product->image_url }}",
    "description": "{{ strip_tags($product->description) }}",
    "brand": {
        "@type": "Brand",
        "name": "ألبان وسوبرماركت التوحيد"
    },
    "offers": {
        "@type": "Offer",
        "priceCurrency": "EGP",
        "price": "{{ $finalPrice }}",
        "availability": "{{ $isInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
    }
}
</script>
@endpush

@section('content')
<div class="py-8 bg-gradient-to-b from-orange-50/20 via-canvas-base to-canvas-base min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-500 flex-wrap" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-red-600 transition-colors">الرئيسية</a>
            <span>/</span>
            @if($product->category)
                <a href="{{ route('category.product', $product->category->id) }}" class="hover:text-red-600 transition-colors">
                    {{ $product->category->name }}
                </a>
                <span>/</span>
            @endif
            <span class="text-gray-900 font-black">{{ $product->name }}</span>
        </nav>

        <!-- Product Details Main Container -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-orange-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12"
             x-data="{ 
                 basePrice: {{ (float)$finalPrice }},
                 isWeighted: {{ $isWeighted ? 'true' : 'false' }},
                 portionType: '{{ $isWeighted ? '250g' : 'piece' }}',
                 customAmount: 20,
                 notes: '',
                 quantity: 1, 
                 maxStock: {{ $product->stock_quantity ?: 99 }},
                 adding: false,

                 get unitPrice() {
                     if (!this.isWeighted || this.portionType === 'piece') {
                         return this.basePrice;
                     }
                     if (this.portionType === '125g') return Math.round(this.basePrice * 0.125 * 100) / 100;
                     if (this.portionType === '250g') return Math.round(this.basePrice * 0.25 * 100) / 100;
                     if (this.portionType === '500g') return Math.round(this.basePrice * 0.5 * 100) / 100;
                     if (this.portionType === '1000g' || this.portionType === '1kg') return this.basePrice;
                     if (this.portionType === 'custom_price') return parseFloat(this.customAmount) || 0;
                     return this.basePrice;
                 },

                 get totalPrice() {
                     return (this.unitPrice * this.quantity).toFixed(2);
                 },

                 get approxGrams() {
                     if (this.portionType === 'custom_price' && this.basePrice > 0) {
                         return Math.round(((parseFloat(this.customAmount) || 0) / this.basePrice) * 1000);
                     }
                     return 0;
                 },

                 increase() { if (this.quantity < this.maxStock) this.quantity++ },
                 decrease() { if (this.quantity > 1) this.quantity-- },

                 addToCart() {
                     if (this.portionType === 'custom_price' && (!this.customAmount || this.customAmount <= 0)) {
                         toastr.warning('يرجى إدخال المبلغ المطلوب');
                         return;
                     }
                     this.adding = true;
                     fetch('{{ route('AddToCart', $product->id) }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                             'Accept': 'application/json'
                         },
                         body: JSON.stringify({ 
                             quantity: this.quantity,
                             portion_type: this.portionType,
                             custom_amount: this.portionType === 'custom_price' ? parseFloat(this.customAmount) : null,
                             notes: this.notes
                         })
                     })
                     .then(res => res.json())
                     .then(data => {
                         this.adding = false;
                         if (data.success || data.status) {
                             toastr.success(data.message || 'تمت إضافة المنتج للسلة بنجاح');
                             window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count ?? data.count } }));
                         } else {
                             toastr.error(data.message || 'حدث خطأ أثناء الإضافة');
                         }
                     })
                     .catch(err => {
                         this.adding = false;
                         toastr.error('حدث خطأ أثناء الإضافة بالسلة');
                     });
                 }
             }">
            
            <!-- Right: Product Image with Clean Display and Gallery Thumbnails -->
            <div class="space-y-4"
                 x-data="{
                     activeImage: '{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}'
                 }">
                <div class="relative bg-gradient-to-b from-orange-50/40 to-red-50/20 rounded-3xl p-6 flex items-center justify-center border border-orange-100 overflow-hidden group min-h-[320px] sm:min-h-[420px]">
                    @if($hasDiscount)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3.5 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md">
                                خصم {{ $discountPercent }}%
                            </span>
                        </div>
                    @endif

                    @if($isWeighted)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 rounded-full text-[11px] font-black bg-amber-100 text-amber-900 border border-amber-300 shadow-xs">
                                يباع بالوزن والطلب الحر
                            </span>
                        </div>
                    @endif

                    <img :src="activeImage" 
                         alt="{{ $product->name }}" 
                         class="max-w-full max-h-[380px] object-contain transition-transform duration-500 group-hover:scale-105">
                </div>

                <!-- Multiple Images Thumbnails (if any) -->
                @if($product->images->isNotEmpty())
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @if($product->image_url)
                            <button type="button" 
                                    @click="activeImage = '{{ $product->image_url }}'"
                                    :class="activeImage === '{{ $product->image_url }}' ? 'ring-2 ring-red-600 border-transparent' : 'border-gray-200 hover:border-orange-300'"
                                    class="w-16 h-16 rounded-2xl bg-white border p-1 shrink-0 overflow-hidden transition-all cursor-pointer">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                            </button>
                        @endif

                        @foreach($product->images as $galleryImg)
                            <button type="button" 
                                    @click="activeImage = '{{ $galleryImg->image_url }}'"
                                    :class="activeImage === '{{ $galleryImg->image_url }}' ? 'ring-2 ring-red-600 border-transparent' : 'border-gray-200 hover:border-orange-300'"
                                    class="w-16 h-16 rounded-2xl bg-white border p-1 shrink-0 overflow-hidden transition-all cursor-pointer">
                                <img src="{{ $galleryImg->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Left: Info & Weighted Selection Controls -->
            <div class="flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <!-- Category Tag -->
                    @if($product->category)
                        <a href="{{ route('category.product', $product->category->id) }}" 
                           class="inline-block px-3 py-1 rounded-full bg-orange-50 text-red-700 border border-orange-200 text-xs font-black hover:bg-orange-100 transition-colors">
                            {{ $product->category->name }}
                        </a>
                    @endif

                    <!-- Product Title -->
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Stock Status & Fresh Guarantee -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        @if($isInStock)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>متوفر بالمخزون والفروع</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-bold border border-gray-300">
                                <span>غير متوفر حالياً</span>
                            </span>
                        @endif

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 font-bold border border-amber-200">
                            <span>طازج يومياً من مزارع ومصانع التوحيد</span>
                        </span>
                    </div>

                    <!-- Pricing Row (Calculated dynamically according to chosen portion) -->
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-orange-50/80 to-red-50/50 border-2 border-orange-200 space-y-1">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-600 block">سعر الطلب المحدد:</span>
                                <span class="text-2xl sm:text-3xl font-black text-red-600 tabular-nums" x-text="totalPrice + ' ج.م'">
                                    {{ format_currency($finalPrice) }}
                                </span>
                            </div>
                            <div class="text-left">
                                <span class="text-[11px] text-gray-500 block">سعر الكيلو / الوحدة:</span>
                                <span class="text-sm font-black text-gray-800 tabular-nums">{{ format_currency($finalPrice) }}</span>
                            </div>
                        </div>

                        @if($hasDiscount)
                            <div class="pt-1 flex items-center gap-2 text-xs">
                                <span class="text-gray-400 line-through tabular-nums">{{ format_currency($product->price) }}</span>
                                <span class="text-[11px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                                    وفرت {{ format_currency($product->price - $product->discount_price) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- 🧀 Portion / Weight Selector for Cheese & Dairy -->
                    @if($isWeighted)
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between">
                                <label class="font-black text-xs text-gray-900 block">
                                    اختر الوزن أو اطلب بمبلغ مالي محدد:
                                </label>
                                <span class="text-[11px] text-orange-600 font-bold">تقطيع وتجهيز طازج</span>
                            </div>

                            <!-- Fast Radio Chips for standard portions -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                                <button type="button" 
                                        @click="portionType = '125g'"
                                        :class="portionType === '125g' ? 'border-red-600 bg-red-600 text-white shadow-sm font-black' : 'border-gray-200 bg-gray-50 hover:bg-orange-50 text-gray-800 font-bold'"
                                        class="py-2.5 px-3 rounded-xl border-2 text-center transition-all cursor-pointer">
                                    <span class="block">ثمن كيلو</span>
                                    <span class="text-[10px] block opacity-85" x-text="'(' + (basePrice * 0.125).toFixed(1) + ' ج.م)'">125 جم</span>
                                </button>

                                <button type="button" 
                                        @click="portionType = '250g'"
                                        :class="portionType === '250g' ? 'border-red-600 bg-red-600 text-white shadow-sm font-black' : 'border-gray-200 bg-gray-50 hover:bg-orange-50 text-gray-800 font-bold'"
                                        class="py-2.5 px-3 rounded-xl border-2 text-center transition-all cursor-pointer">
                                    <span class="block">ربع كيلو</span>
                                    <span class="text-[10px] block opacity-85" x-text="'(' + (basePrice * 0.25).toFixed(1) + ' ج.م)'">250 جم</span>
                                </button>

                                <button type="button" 
                                        @click="portionType = '500g'"
                                        :class="portionType === '500g' ? 'border-red-600 bg-red-600 text-white shadow-sm font-black' : 'border-gray-200 bg-gray-50 hover:bg-orange-50 text-gray-800 font-bold'"
                                        class="py-2.5 px-3 rounded-xl border-2 text-center transition-all cursor-pointer">
                                    <span class="block">نصف كيلو</span>
                                    <span class="text-[10px] block opacity-85" x-text="'(' + (basePrice * 0.5).toFixed(1) + ' ج.م)'">500 جم</span>
                                </button>

                                <button type="button" 
                                        @click="portionType = '1000g'"
                                        :class="portionType === '1000g' ? 'border-red-600 bg-red-600 text-white shadow-sm font-black' : 'border-gray-200 bg-gray-50 hover:bg-orange-50 text-gray-800 font-bold'"
                                        class="py-2.5 px-3 rounded-xl border-2 text-center transition-all cursor-pointer">
                                    <span class="block">كيلو كامل</span>
                                    <span class="text-[10px] block opacity-85" x-text="'(' + basePrice.toFixed(1) + ' ج.م)'">1000 جم</span>
                                </button>
                            </div>

                            <!-- Custom Price Option Button (زي عايز بـ 20 جنيه أو بـ 50 جنيه) -->
                            <div class="pt-1">
                                <button type="button" 
                                        @click="portionType = 'custom_price'"
                                        :class="portionType === 'custom_price' ? 'border-orange-500 bg-orange-50 text-orange-950 font-black' : 'border-gray-200 bg-gray-50 text-gray-700 hover:bg-orange-50 font-bold'"
                                        class="w-full py-2 px-3 rounded-xl border-2 flex items-center justify-between text-xs transition-all cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="portionType === 'custom_price' ? 'bg-orange-600' : 'bg-gray-400'"></span>
                                        <span>طلب بمبلغ مالي محدد (مثال: بـ 20 جنيه / 50 جنيه...)</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-orange-700">تحديد المبلغ ↓</span>
                                </button>

                                <!-- Custom Amount Input Field -->
                                <div x-show="portionType === 'custom_price'" x-cloak x-transition class="mt-2.5 p-3 rounded-xl bg-orange-50/70 border border-orange-200 space-y-2 text-xs">
                                    <label class="font-black text-gray-900 block">اكتب المبلغ المطلوب (بالجنيه):</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                               x-model.number="customAmount" 
                                               placeholder="مثال: 20" 
                                               min="5" 
                                               max="5000"
                                               class="w-32 bg-white border border-orange-300 focus:border-red-600 rounded-lg px-3 py-1.5 text-xs font-black text-gray-900 outline-none">
                                        <span class="font-bold text-gray-700">جنيه مصري</span>
                                        <span class="text-[11px] text-gray-500 mr-auto" x-show="approxGrams > 0">
                                            يعادل تقريباً <strong class="text-orange-700 font-black" x-text="approxGrams"></strong> جرام
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Preparation & Slicing Notes -->
                            <div class="pt-2">
                                <label for="productNotes" class="font-bold text-[11px] text-gray-700 block mb-1">
                                    ملاحظات التقطيع والتغليف (اختياري):
                                </label>
                                <input type="text" 
                                       id="productNotes"
                                       x-model="notes" 
                                       placeholder="مثال: تقطيع شرائح رفيعة، تغليف في طبقين، قليل الملح..."
                                       class="w-full bg-gray-50 border border-gray-200 focus:border-red-600 focus:bg-white rounded-xl px-3 py-2 text-xs text-gray-900 outline-none transition-all">
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    @if($product->description)
                        <div class="text-xs text-gray-600 leading-relaxed pt-2 border-t border-gray-100">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @endif
                </div>

                <!-- Action Controls -->
                <div class="space-y-3 pt-4 border-t border-orange-100">
                    @if($isInStock)
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <!-- Quantity Counter -->
                            <div class="flex items-center justify-between border-2 border-orange-200 bg-gray-50 rounded-2xl p-1.5 w-full sm:w-36">
                                <button type="button" @click="decrease()" class="w-8 h-8 rounded-xl bg-white hover:bg-orange-100 text-gray-900 font-black flex items-center justify-center transition-colors cursor-pointer">
                                    -
                                </button>
                                <span x-text="quantity" class="font-black text-sm text-gray-900 tabular-nums px-2">1</span>
                                <button type="button" @click="increase()" class="w-8 h-8 rounded-xl bg-white hover:bg-orange-100 text-gray-900 font-black flex items-center justify-center transition-colors cursor-pointer">
                                    +
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="button" 
                                    @click="addToCart()"
                                    :disabled="adding"
                                    class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 active:scale-98 text-white text-xs sm:text-sm font-black transition-all shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 cursor-pointer">
                                <svg x-show="!adding" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <svg x-show="adding" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span x-text="adding ? 'جاري الإضافة...' : 'أضف إلى السلة'">أضف إلى السلة</span>
                            </button>
                        </div>
                    @endif

                    <!-- WhatsApp Direct Order Button -->
                    <a href="https://wa.me/{{ $cleanWhatsApp }}?text={{ urlencode('مرحباً ألبان وسوبرماركت التوحيد، أود طلب المنتج: ' . $product->name . ' بسعر ' . format_currency($finalPrice)) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-full py-3 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                        </svg>
                        <span>اطلب فوراً عبر واتساب</span>
                    </a>

                    <!-- Supermarket Delivery Highlights -->
                    <div class="grid grid-cols-2 gap-2 pt-2 text-[11px] text-gray-600">
                        <div class="flex items-center gap-1.5 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                            <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>توصيل سريع بالعامرية ومريوط</span>
                        </div>
                        <div class="flex items-center gap-1.5 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                            <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>متاح الاستلام من 4 فروع</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Customer Reviews & Ratings Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-orange-100 shadow-sm space-y-6"
             x-data="{
                 rating: 5,
                 comment: '',
                 submitting: false,
                 submitReview() {
                     this.submitting = true;
                     fetch('{{ route('reviews.store') }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                             'Accept': 'application/json'
                         },
                         body: JSON.stringify({
                             product_id: {{ $product->id }},
                             rating: this.rating,
                             comment: this.comment
                         })
                     })
                     .then(res => res.json())
                     .then(data => {
                         this.submitting = false;
                         if (data.success) {
                             toastr.success(data.message || 'تم إرسال تقييمك بنجاح');
                             this.comment = '';
                             setTimeout(() => location.reload(), 1200);
                         } else {
                             toastr.error(data.message || 'تعذر إرسال التقييم');
                         }
                     })
                     .catch(() => {
                         this.submitting = false;
                         toastr.error('حدث خطأ أثناء إرسال التقييم');
                     });
                 }
             }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-orange-100 pb-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900">تقييمات وآراء العملاء</h2>
                    <p class="text-xs text-gray-500 mt-0.5">آراء المتسوقين حول جودة هذا المنتج من ألبان وسوبرماركت التوحيد</p>
                </div>
                
                @php
                    $reviewsCount = $product->reviews->count();
                    $avgRating = $reviewsCount > 0 ? round($product->reviews->avg('rating'), 1) : 0;
                @endphp

                <div class="flex items-center gap-3 bg-orange-50 px-4 py-2 rounded-2xl border border-orange-200 shrink-0">
                    <div class="text-2xl font-black text-red-600 tabular-nums">{{ $avgRating > 0 ? $avgRating : 'جديد' }}</div>
                    <div class="text-xs">
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[11px] text-gray-500 font-bold">({{ $reviewsCount }} تقييم)</span>
                    </div>
                </div>
            </div>

            <!-- Reviews List & Submission Form -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: List of Reviews -->
                <div class="lg:col-span-7 space-y-4">
                    @if($product->reviews->isNotEmpty())
                        <div class="divide-y divide-gray-100">
                            @foreach($product->reviews as $review)
                                <div class="py-3.5 first:pt-0 last:pb-0 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-red-600 to-orange-500 text-white font-black text-xs flex items-center justify-center">
                                                {{ mb_substr($review->user?->name ?? 'ع', 0, 1) }}
                                            </div>
                                            <span class="font-black text-xs text-gray-900">{{ $review->user?->name ?? 'عميل موثق' }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $review->created_at?->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div class="flex text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= (int)$review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>

                                    @if($review->comment)
                                        <p class="text-xs text-gray-600 leading-relaxed bg-gray-50/70 p-2.5 rounded-xl border border-gray-100">
                                            {{ $review->comment }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                            <span class="text-2xl">⭐</span>
                            <p class="text-xs font-bold text-gray-700">كن أول من يقيّم هذا المنتج!</p>
                            <p class="text-[11px] text-gray-500">رأيك يهمنا ويساعد الآخرين على اتخاذ القرار الأفضل.</p>
                        </div>
                    @endif
                </div>

                <!-- Right: Submit Review Form -->
                <div class="lg:col-span-5 bg-gray-50/70 rounded-2xl p-5 border border-orange-100 space-y-4">
                    <h3 class="font-black text-xs sm:text-sm text-gray-900">أضف تقييمك وتجربتك</h3>

                    @auth
                        <form @submit.prevent="submitReview()" class="space-y-3">
                            <div>
                                <label class="font-bold text-[11px] text-gray-700 block mb-1">التقييم بالنجوم:</label>
                                <div class="flex items-center gap-1.5">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button" 
                                                @click="rating = star" 
                                                class="text-2xl transition-transform hover:scale-110 cursor-pointer"
                                                :class="star <= rating ? 'text-amber-400' : 'text-gray-300'">
                                            ★
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label for="reviewComment" class="font-bold text-[11px] text-gray-700 block mb-1">تعليقك (اختياري):</label>
                                <textarea id="reviewComment" 
                                          x-model="comment" 
                                          rows="3" 
                                          placeholder="ما رأيك في جودة المنتج، الطعم، والتغليف؟"
                                          class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs text-gray-900 outline-none focus:border-red-500 transition-colors"></textarea>
                            </div>

                            <button type="submit" 
                                    :disabled="submitting"
                                    class="w-full py-2.5 px-4 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-black transition-all disabled:opacity-50 cursor-pointer flex items-center justify-center gap-1.5 shadow-sm">
                                <span x-show="!submitting">إرسال التقييم</span>
                                <span x-show="submitting">جاري الإرسال...</span>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-4 space-y-2">
                            <p class="text-xs text-gray-600 font-bold">يرجى تسجيل الدخول أولاً لتتمكن من إضافة تقييمك للمنتج.</p>
                            <a href="{{ route('login') }}" class="inline-block py-2 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-black text-xs transition-colors">
                                تسجيل الدخول
                            </a>
                        </div>
                    @endauth
                </div>

            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between border-b border-orange-200 pb-3">
                    <div>
                        <span class="text-xs font-black text-red-600 block">قد يعجبك أيضاً</span>
                        <h2 class="text-lg sm:text-2xl font-black text-gray-900">منتجات ذات صلة من نفس القسم</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $related)
                        <x-storefront.ui.product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
