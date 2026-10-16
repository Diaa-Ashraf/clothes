{{-- resources/views/storefront/products/show.blade.php --}}
@extends('layouts.storefront')

@section('title', $product->name . ' | ' . settings('site_name', 'أزياء لافيستا'))
@section('meta_description', Str::limit(strip_tags($product->description), 160))

@php
    $finalPrice = (float)($product->final_price ?? $product->discount_price ?? $product->price);
    $hasDiscount = ($product->discount_price && $product->discount_price < $product->price);
    $discountPercent = $hasDiscount && $product->price > 0 ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0;
    $isInStock = ($product->is_available && ($product->stock_quantity > 0 || $product->quantity > 0));
    $storeWhatsApp = settings('store_whatsapp', '01000000000');
    $cleanWhatsApp = preg_replace('/[^0-9]/', '', $storeWhatsApp);
    
    $variants = $product->variants()->with('attributeValues.attribute')->where('is_active', true)->get();
    $hasVariants = $variants->isNotEmpty();
    $firstVariant = $hasVariants ? $variants->first() : null;
    $initialVariantId = $firstVariant ? $firstVariant->id : 'null';
    $initialPrice = $firstVariant ? (float)$firstVariant->effective_price : $finalPrice;
    $initialLabel = $firstVariant 
        ? ($firstVariant->attributeValues->map(fn($av) => $av->value)->implode(' - ') ?: ($firstVariant->sku ?: 'قياسي')) 
        : 'قياسي';
@endphp

@push('styles')
<!-- Schema.org JSON-LD Structured Data for Fashion Product -->
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": [
        "{{ $product->image_url }}"
        @if($product->images->isNotEmpty())
            @foreach($product->images as $galImg)
                ,"{{ $galImg->image_url }}"
            @endforeach
        @endif
    ],
    "description": "{{ addslashes(strip_tags($product->description)) }}",
    "sku": "{{ $product->sku ?? 'TAW-' . $product->id }}",
    "mpn": "{{ $product->sku ?? 'TAW-' . $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand?->name ?? settings('site_name', 'أزياء لافيستا') }}"
    },
    "category": "{{ $product->category?->name ?? 'الملابس والأزياء' }}",
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "EGP",
        "price": "{{ $initialPrice }}",
        "priceValidUntil": "{{ now()->addMonths(6)->toDateString() }}",
        "availability": "{{ $isInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@type": "Organization",
            "name": "{{ settings('site_name', 'أزياء لافيستا') }}"
        }
    }
}
</script>
@endpush

@section('content')
<div class="py-10 bg-white min-h-screen" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63] flex-wrap" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('site.products') }}" class="hover:text-[#382416] transition-colors">الكتالوج</a>
            <span>/</span>
            @if($product->category)
                <a href="{{ route('category.product', $product->category->id) }}" class="hover:text-[#382416] transition-colors">
                    {{ $product->category->name }}
                </a>
                <span>/</span>
            @endif
            <span class="text-[#2B2118] font-bold">{{ $product->name }}</span>
        </nav>

        <!-- Product Details Main Container -->
        <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-10 border border-[#EADFC8] shadow-xs grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12"
             x-data="{ 
                 basePrice: {{ (float)$finalPrice }},
                 selectedVariantId: {{ $initialVariantId }},
                 selectedVariantLabel: '{{ addslashes($initialLabel) }}',
                 unitPrice: {{ (float)$initialPrice }},
                 quantity: 1, 
                 maxStock: {{ $product->stock_quantity ?: 99 }},
                 adding: false,
                 notes: '',
                 sizeGuideOpen: false,

                 // Size fit calculator state
                 userHeight: 175,
                 userWeight: 75,
                 userFitPreference: 'regular',
                 sizeGuideTab: 'tables',
                 sizeGenderTab: '{{ $product->gender ?: 'men' }}',

                 get recommendedSize() {
                     const h = parseInt(this.userHeight) || 170;
                     const w = parseInt(this.userWeight) || 70;
                     const bmi = w / Math.pow(h / 100, 2);
                     let base = 'M';
                     if (w < 60 || bmi < 20) base = 'S';
                     else if (w <= 72 && bmi < 24) base = 'M';
                     else if (w <= 84 && bmi < 27) base = 'L';
                     else if (w <= 96 && bmi < 31) base = 'XL';
                     else if (w <= 110 && bmi < 35) base = '2XL';
                     else base = '3XL';

                     if (this.userFitPreference === 'loose') {
                         if (base === 'S') base = 'M';
                         else if (base === 'M') base = 'L';
                         else if (base === 'L') base = 'XL';
                         else if (base === 'XL') base = '2XL';
                         else if (base === '2XL') base = '3XL';
                     } else if (this.userFitPreference === 'slim') {
                         if (base === '3XL') base = '2XL';
                         else if (base === '2XL') base = 'XL';
                         else if (base === 'XL') base = 'L';
                         else if (base === 'L') base = 'M';
                     }
                     return base;
                 },

                 get totalPrice() {
                     return (this.unitPrice * this.quantity).toFixed(2);
                 },

                 selectVariant(vId, vPrice, vStock, vLabel) {
                     this.selectedVariantId = vId;
                     this.unitPrice = vPrice;
                     this.selectedVariantLabel = vLabel;
                     this.maxStock = vStock > 0 ? vStock : 1;
                     if (this.quantity > this.maxStock) {
                         this.quantity = this.maxStock;
                     }
                 },

                 increase() { if (this.quantity < this.maxStock) this.quantity++ },
                 decrease() { if (this.quantity > 1) this.quantity-- },

                 orderViaWhatsApp() {
                     const phone = '{{ $cleanWhatsApp }}';
                     const prodName = '{{ addslashes($product->name) }}';
                     const label = this.selectedVariantLabel ? this.selectedVariantLabel : 'الأساسي';
                     const qty = this.quantity;
                     const total = this.totalPrice + ' ج.م';
                     const currentUrl = window.location.href;

                     const msg = 'مرحباً {{ addslashes(settings('site_name', 'أزياء لافيستا')) }} 🌿\n' +
                                 'أود طلب القطعة التالية بشكل مباشر:\n' +
                                 '• الموديل: ' + prodName + '\n' +
                                 '• المقاس / الاختيار: ' + label + '\n' +
                                 '• الكمية: ' + qty + '\n' +
                                 '• الإجمالي التقريبي: ' + total + '\n' +
                                 '• رابط الموديل: ' + currentUrl + '\n\n' +
                                 'بيانات التوصيل:\n' +
                                 '• الاسم:\n' +
                                 '• رقم الهاتف:\n' +
                                 '• المحافظة والعنوان بالتفصيل:';

                     const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(msg);
                     window.open(url, '_blank');
                 },

                 addToCart() {
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
                             variant_id: this.selectedVariantId,
                             notes: this.notes
                         })
                     })
                     .then(res => res.json())
                     .then(data => {
                         this.adding = false;
                         if (data.success || data.status) {
                             toastr.success(data.message || 'تمت إضافة القطعة إلى حقيبة التسوق بنجاح');
                             window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count ?? data.count } }));
                         } else {
                             toastr.error(data.message || 'حدث خطأ أثناء الإضافة');
                         }
                     })
                     .catch(err => {
                         this.adding = false;
                         toastr.error('حدث خطأ أثناء الإضافة للسلة');
                     });
                 }
             }">
            
            <!-- Right: Product Image with Clean Display and Gallery Thumbnails -->
            <div class="space-y-4"
                 x-data="{
                     activeImage: '{{ $product->image_url ?? asset('assets/site/img/product-placeholder.png') }}'
                 }">
                <div class="relative bg-[#F3E9D2]/30 rounded-3xl p-6 flex items-center justify-center border border-[#EADFC8] overflow-hidden group min-h-[340px] sm:min-h-[460px]">
                    @if($hasDiscount)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#A9552F] text-[#FBF6EA] shadow-sm">
                                خصم {{ $discountPercent }}%
                            </span>
                        </div>
                    @endif

                    @if($product->brand)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] shadow-2xs">
                                {{ $product->brand->name }}
                            </span>
                        </div>
                    @endif

                    <img :src="activeImage" 
                         alt="{{ $product->name }}" 
                         class="max-w-full max-h-[420px] object-contain transition-transform duration-700 group-hover:scale-104">
                </div>

                <!-- Multiple Images Thumbnails (if any) -->
                @if($product->images->isNotEmpty())
                    <div class="flex items-center gap-3 overflow-x-auto pb-2 no-scrollbar">
                        @if($product->image_url)
                            <button type="button" 
                                    @click="activeImage = '{{ $product->image_url }}'"
                                    :class="activeImage === '{{ $product->image_url }}' ? 'ring-2 ring-[#382416] border-transparent' : 'border-[#EADFC8] hover:border-[#CDB78F]'"
                                    class="w-16 h-16 rounded-2xl bg-white border p-1 shrink-0 overflow-hidden transition-all cursor-pointer">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl">
                            </button>
                        @endif

                        @foreach($product->images as $galleryImg)
                            <button type="button" 
                                    @click="activeImage = '{{ $galleryImg->image_url }}'"
                                    :class="activeImage === '{{ $galleryImg->image_url }}' ? 'ring-2 ring-[#382416] border-transparent' : 'border-[#EADFC8] hover:border-[#CDB78F]'"
                                    class="w-16 h-16 rounded-2xl bg-white border p-1 shrink-0 overflow-hidden transition-all cursor-pointer">
                                <img src="{{ $galleryImg->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Left: Info & Variant Selection Controls -->
            <div class="flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <!-- Category & Brand Tags -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($product->category)
                            <a href="{{ route('category.product', $product->category->id) }}" 
                               class="inline-block px-3 py-1 rounded-full bg-[#F3E9D2] text-[#382416] border border-[#EADFC8] text-xs font-semibold hover:bg-[#EADFC8] transition-colors">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        @if($product->gender)
                            <span class="inline-block px-3 py-1 rounded-full bg-[#FBF6EA] text-[#6B5642] border border-[#EADFC8] text-xs font-medium">
                                {{ $product->gender == 'men' ? 'رجالي' : ($product->gender == 'women' ? 'نسائي' : ($product->gender == 'kids' ? 'أطفال' : 'للجنسين')) }}
                            </span>
                        @endif
                        @if($product->season)
                            <span class="inline-block px-3 py-1 rounded-full bg-[#FBF6EA] text-[#6B5642] border border-[#EADFC8] text-xs font-medium">
                                {{ $product->season == 'summer' ? 'صيفي' : ($product->season == 'winter' ? 'شتوي' : 'جميع الفصول') }}
                            </span>
                        @endif
                    </div>

                    <!-- Product Title -->
                    <h1 class="text-2xl sm:text-3xl font-serif font-black text-[#2B2118] leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Stock Status -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        @if($isInStock)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F3E9D2] text-[#382416] font-semibold border border-[#CDB78F]">
                                <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                                <span>متوفر بالمخزون والبوتيكات</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EADFC8] text-[#6B5642] font-semibold border border-[#CDB78F]">
                                <span>غير متوفر حالياً</span>
                            </span>
                        @endif

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FBF6EA] text-[#6B5642] font-medium border border-[#EADFC8]">
                            <span>خامات طبيعية فاخرة 100%</span>
                        </span>
                    </div>

                    <!-- Pricing Row -->
                    <div class="p-4 rounded-2xl bg-[#F3E9D2]/40 border border-[#EADFC8] space-y-1">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="text-xs font-semibold text-[#8F7B63] block">السعر:</span>
                                <span class="text-2xl sm:text-3xl font-black text-[#382416] tabular-nums" x-text="totalPrice + ' ج.م'">
                                    {{ format_currency($initialPrice) }}
                                </span>
                            </div>
                            @if($hasDiscount)
                                <div class="text-left">
                                    <span class="text-[11px] text-[#8F7B63] block line-through tabular-nums">{{ format_currency($product->price) }}</span>
                                    <span class="text-xs font-bold text-[#A9552F] bg-[#A9552F]/10 px-2 py-0.5 rounded-md">
                                        وفرت {{ format_currency($product->price - $product->discount_price) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 👗 Size & Color Variant Selector for Clothing -->
                    <div class="space-y-3 pt-2 border-t border-[#EADFC8]">
                        <div class="flex items-center justify-between">
                            <label class="font-bold text-xs text-[#2B2118] block">
                                @if($hasVariants)
                                    المقاس واللون المتاح:
                                @else
                                    المقاس والأبعاد:
                                @endif
                            </label>
                            
                            <!-- Smart Size Guide Trigger Button -->
                            <button type="button" 
                                    @click="sizeGuideOpen = true" 
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-[#AC9653] hover:text-[#382416] transition-colors cursor-pointer group">
                                <svg class="w-4 h-4 text-[#AC9653] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span class="underline underline-offset-4">دليل المقاسات التفاعلي</span>
                            </button>
                        </div>

                        @if($hasVariants)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                                @foreach($variants as $variant)
                                    @php
                                        $vLabel = $variant->attributeValues->map(fn($av) => $av->value)->implode(' - ') ?: ($variant->sku ?: 'قياسي');
                                        $vPrice = (float)$variant->effective_price;
                                        $vStock = (int)$variant->stock_quantity;
                                        $vInStock = $vStock > 0;
                                    @endphp
                                    <button type="button" 
                                            @if($vInStock)
                                                @click="selectVariant({{ $variant->id }}, {{ $vPrice }}, {{ $vStock }}, '{{ addslashes($vLabel) }}')"
                                            @endif
                                            :class="selectedVariantId === {{ $variant->id }} 
                                                ? 'border-[#382416] bg-[#382416] text-[#FBF6EA] shadow-xs font-bold' 
                                                : 'border-[#EADFC8] bg-[#FDFBF7] text-[#2B2118] hover:border-[#CDB78F] {{ !$vInStock ? 'opacity-40 cursor-not-allowed' : '' }}'"
                                            class="py-2.5 px-3 rounded-xl border text-center transition-all cursor-pointer flex flex-col justify-center">
                                        <span class="font-bold text-xs">{{ $vLabel }}</span>
                                        <span class="text-[10px] opacity-80 mt-0.5 tabular-nums">{{ format_currency($vPrice) }}</span>
                                        @if(!$vInStock)
                                            <span class="text-[9px] text-[#A9552F] font-bold">نفد من المخزون</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Apparel Specifications Table -->
                    <div class="pt-3 border-t border-[#EADFC8] space-y-2 text-xs">
                        <span class="font-bold text-[#2B2118] block">مواصفات القطعة:</span>
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            @if($product->material)
                                <div class="p-2 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] flex items-center justify-between">
                                    <span class="text-[#8F7B63]">الخامة:</span>
                                    <span class="font-bold text-[#2B2118]">{{ $product->material }}</span>
                                </div>
                            @endif
                            @if($product->fit)
                                <div class="p-2 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] flex items-center justify-between">
                                    <span class="text-[#8F7B63]">القصة (Fit):</span>
                                    <span class="font-bold text-[#2B2118]">{{ $product->fit }}</span>
                                </div>
                            @endif
                            @if($product->sku)
                                <div class="p-2 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] flex items-center justify-between">
                                    <span class="text-[#8F7B63]">كود الصنف:</span>
                                    <span class="font-mono font-bold text-[#2B2118]">{{ $product->sku }}</span>
                                </div>
                            @endif
                            @if($product->brand)
                                <div class="p-2 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] flex items-center justify-between">
                                    <span class="text-[#8F7B63]">الماركة:</span>
                                    <span class="font-bold text-[#2B2118]">{{ $product->brand->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="text-xs text-[#6B5642] leading-relaxed pt-3 border-t border-[#EADFC8]">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @endif
                </div>

                <!-- Action Controls -->
                <div class="space-y-3 pt-4 border-t border-[#EADFC8]">
                    @if($isInStock)
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <!-- Quantity Counter -->
                            <div class="flex items-center justify-between border border-[#EADFC8] bg-[#F3E9D2]/30 rounded-2xl p-1.5 w-full sm:w-36">
                                <button type="button" @click="decrease()" class="w-8 h-8 rounded-xl bg-white hover:bg-[#F3E9D2] text-[#2B2118] font-black flex items-center justify-center transition-colors cursor-pointer border border-[#EADFC8]">
                                    -
                                </button>
                                <span x-text="quantity" class="font-black text-sm text-[#2B2118] tabular-nums px-2">1</span>
                                <button type="button" @click="increase()" class="w-8 h-8 rounded-xl bg-white hover:bg-[#F3E9D2] text-[#2B2118] font-black flex items-center justify-center transition-colors cursor-pointer border border-[#EADFC8]">
                                    +
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="button" 
                                    @click="addToCart()"
                                    :disabled="adding"
                                    class="flex-1 py-4 px-8 bg-[#CBA367] hover:bg-[#b88f55] active:scale-98 text-white text-xs sm:text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer rounded-2xl">
                                <svg x-show="!adding" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <svg x-show="adding" x-cloak class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span x-text="adding ? 'جاري الإضافة...' : 'أضف إلى حقيبة التسوق'">أضف إلى حقيبة التسوق</span>
                            </button>
                        </div>
                    @endif

                    <!-- WhatsApp 1-Click Express Order Button -->
                    <button type="button"
                            @click="orderViaWhatsApp()"
                            class="w-full py-3.5 px-5 bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#075E54] border border-[#25D366]/40 text-xs sm:text-sm font-bold rounded-2xl transition-all flex items-center justify-center gap-2.5 cursor-pointer shadow-xs hover:shadow-sm">
                        <svg class="w-5 h-5 text-[#25D366] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                        </svg>
                        <span class="flex items-center gap-1.5 flex-wrap justify-center">
                            <span>طلب سريع بنقرة واحدة عبر واتساب</span>
                            <span class="text-[10px] bg-white text-[#075E54] px-2 py-0.5 rounded-full border border-[#25D366]/30 font-bold">بدون تسجيل</span>
                        </span>
                    </button>

                    <!-- Boutique Assurance Highlights -->
                    <div class="grid grid-cols-2 gap-2 pt-2 text-[11px] text-[#6B5642]">
                        <div class="flex items-center gap-1.5 p-2.5 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8]">
                            <svg class="w-4 h-4 text-[#A9552F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>حق المعاينة والقياس عند الاستلام</span>
                        </div>
                        <div class="flex items-center gap-1.5 p-2.5 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8]">
                            <svg class="w-4 h-4 text-[#A9552F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>استبدال سهل وسريع للمقاسات</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📏 Interactive Smart Size Guide Modal -->
            <div x-show="sizeGuideOpen" 
                 x-cloak
                 @keydown.escape.window="sizeGuideOpen = false"
                 class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                 role="dialog"
                 aria-modal="true">
                
                <!-- Backdrop -->
                <div x-show="sizeGuideOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sizeGuideOpen = false"
                     class="fixed inset-0 bg-[#2B2118]/70 backdrop-blur-xs"></div>

                <!-- Modal Dialog -->
                <div x-show="sizeGuideOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white w-full max-w-3xl rounded-3xl shadow-2xl border border-[#EADFC8] overflow-hidden my-8 z-10 text-right"
                     dir="rtl">
                    
                    <!-- Header -->
                    <div class="p-5 sm:p-6 bg-[#AC9653] text-white flex items-center justify-between border-b border-[#BDAB75]/40">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white border border-white/30 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-serif font-black text-base sm:text-lg text-white">دليل المقاسات والأبعاد القياسية</h3>
                                <p class="text-xs text-white/80">قياسات دقيقة بالسنتيمتر (CM) لضمان الاختيار الأنسب لمقاسك</p>
                            </div>
                        </div>

                        <button type="button" 
                                @click="sizeGuideOpen = false" 
                                class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="إغلاق">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="flex items-center border-b border-[#EADFC8] bg-[#FDFBF7] px-4 pt-2 gap-2 overflow-x-auto text-xs font-bold">
                        <button type="button" 
                                @click="sizeGuideTab = 'tables'" 
                                :class="sizeGuideTab === 'tables' ? 'text-[#382416] border-b-2 border-[#AC9653] bg-white rounded-t-xl py-3 px-4 shadow-2xs' : 'text-[#8F7B63] hover:text-[#2B2118] py-3 px-4 cursor-pointer'">
                            جدول المقاسات (سم)
                        </button>
                        <button type="button" 
                                @click="sizeGuideTab = 'calculator'" 
                                :class="sizeGuideTab === 'calculator' ? 'text-[#382416] border-b-2 border-[#AC9653] bg-white rounded-t-xl py-3 px-4 shadow-2xs' : 'text-[#8F7B63] hover:text-[#2B2118] py-3 px-4 cursor-pointer'">
                            حاسبة المقاس التقديري
                        </button>
                        <button type="button" 
                                @click="sizeGuideTab = 'how_to_measure'" 
                                :class="sizeGuideTab === 'how_to_measure' ? 'text-[#382416] border-b-2 border-[#AC9653] bg-white rounded-t-xl py-3 px-4 shadow-2xs' : 'text-[#8F7B63] hover:text-[#2B2118] py-3 px-4 cursor-pointer'">
                            كيف تأخذ مقاسك بدقة؟
                        </button>
                    </div>

                    <!-- Body Content -->
                    <div class="p-5 sm:p-6 max-h-[70vh] overflow-y-auto space-y-6">
                        
                        <!-- TAB 1: Measurement Tables -->
                        <div x-show="sizeGuideTab === 'tables'" class="space-y-4">
                            <!-- Gender switch buttons -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="sizeGenderTab = 'men'"
                                        :class="sizeGenderTab === 'men' ? 'bg-[#382416] text-white shadow-xs' : 'bg-[#F3E9D2]/40 text-[#6B5642] hover:bg-[#F3E9D2]'"
                                        class="py-1.5 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                    رجالي (Men)
                                </button>
                                <button type="button" 
                                        @click="sizeGenderTab = 'women'"
                                        :class="sizeGenderTab === 'women' ? 'bg-[#382416] text-white shadow-xs' : 'bg-[#F3E9D2]/40 text-[#6B5642] hover:bg-[#F3E9D2]'"
                                        class="py-1.5 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                    حريمي (Women)
                                </button>
                                <button type="button" 
                                        @click="sizeGenderTab = 'kids'"
                                        :class="sizeGenderTab === 'kids' ? 'bg-[#382416] text-white shadow-xs' : 'bg-[#F3E9D2]/40 text-[#6B5642] hover:bg-[#F3E9D2]'"
                                        class="py-1.5 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                    أطفال (Kids)
                                </button>
                            </div>

                            <!-- Men's Table -->
                            <div x-show="sizeGenderTab === 'men'" class="overflow-x-auto border border-[#EADFC8] rounded-2xl shadow-2xs">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-[#FDFBF7] text-[#2B2118] border-b border-[#EADFC8] font-bold text-[11px]">
                                        <tr>
                                            <th class="p-3">المقاس القياسي</th>
                                            <th class="p-3">محيط الصدر</th>
                                            <th class="p-3">عرض الكتفين</th>
                                            <th class="p-3">الطول الكلي</th>
                                            <th class="p-3">طول الكم</th>
                                            <th class="p-3">محيط الخصر</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#EADFC8] text-[#382416]">
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">S (Small)</td>
                                            <td class="p-3 tabular-nums">96 - 100 سم</td>
                                            <td class="p-3 tabular-nums">44 سم</td>
                                            <td class="p-3 tabular-nums">70 سم</td>
                                            <td class="p-3 tabular-nums">62 سم</td>
                                            <td class="p-3 tabular-nums">80 - 84 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">M (Medium)</td>
                                            <td class="p-3 tabular-nums">101 - 106 سم</td>
                                            <td class="p-3 tabular-nums">46 سم</td>
                                            <td class="p-3 tabular-nums">72 سم</td>
                                            <td class="p-3 tabular-nums">63 سم</td>
                                            <td class="p-3 tabular-nums">85 - 89 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">L (Large)</td>
                                            <td class="p-3 tabular-nums">107 - 112 سم</td>
                                            <td class="p-3 tabular-nums">48 سم</td>
                                            <td class="p-3 tabular-nums">74 سم</td>
                                            <td class="p-3 tabular-nums">64 سم</td>
                                            <td class="p-3 tabular-nums">90 - 95 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">XL (X-Large)</td>
                                            <td class="p-3 tabular-nums">113 - 118 سم</td>
                                            <td class="p-3 tabular-nums">50 سم</td>
                                            <td class="p-3 tabular-nums">76 سم</td>
                                            <td class="p-3 tabular-nums">65 سم</td>
                                            <td class="p-3 tabular-nums">96 - 102 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">2XL (Double)</td>
                                            <td class="p-3 tabular-nums">119 - 124 سم</td>
                                            <td class="p-3 tabular-nums">52 سم</td>
                                            <td class="p-3 tabular-nums">78 سم</td>
                                            <td class="p-3 tabular-nums">66 سم</td>
                                            <td class="p-3 tabular-nums">103 - 108 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">3XL (Triple)</td>
                                            <td class="p-3 tabular-nums">125 - 130 سم</td>
                                            <td class="p-3 tabular-nums">54 سم</td>
                                            <td class="p-3 tabular-nums">80 سم</td>
                                            <td class="p-3 tabular-nums">67 سم</td>
                                            <td class="p-3 tabular-nums">109 - 115 سم</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Women's Table -->
                            <div x-show="sizeGenderTab === 'women'" class="overflow-x-auto border border-[#EADFC8] rounded-2xl shadow-2xs">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-[#FDFBF7] text-[#2B2118] border-b border-[#EADFC8] font-bold text-[11px]">
                                        <tr>
                                            <th class="p-3">المقاس القياسي</th>
                                            <th class="p-3">محيط الصدر</th>
                                            <th class="p-3">محيط الخصر</th>
                                            <th class="p-3">محيط الأرداف</th>
                                            <th class="p-3">الطول المقترح</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#EADFC8] text-[#382416]">
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">S (36 - 38)</td>
                                            <td class="p-3 tabular-nums">84 - 88 سم</td>
                                            <td class="p-3 tabular-nums">66 - 70 سم</td>
                                            <td class="p-3 tabular-nums">90 - 94 سم</td>
                                            <td class="p-3 tabular-nums">155 - 162 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">M (40 - 42)</td>
                                            <td class="p-3 tabular-nums">89 - 94 سم</td>
                                            <td class="p-3 tabular-nums">71 - 76 سم</td>
                                            <td class="p-3 tabular-nums">95 - 100 سم</td>
                                            <td class="p-3 tabular-nums">160 - 168 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">L (44 - 46)</td>
                                            <td class="p-3 tabular-nums">95 - 101 سم</td>
                                            <td class="p-3 tabular-nums">77 - 83 سم</td>
                                            <td class="p-3 tabular-nums">101 - 107 سم</td>
                                            <td class="p-3 tabular-nums">165 - 172 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">XL (48 - 50)</td>
                                            <td class="p-3 tabular-nums">102 - 108 سم</td>
                                            <td class="p-3 tabular-nums">84 - 90 سم</td>
                                            <td class="p-3 tabular-nums">108 - 114 سم</td>
                                            <td class="p-3 tabular-nums">168 - 175 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">2XL (52+)</td>
                                            <td class="p-3 tabular-nums">109 - 116 سم</td>
                                            <td class="p-3 tabular-nums">91 - 98 سم</td>
                                            <td class="p-3 tabular-nums">115 - 122 سم</td>
                                            <td class="p-3 tabular-nums">170 - 180 سم</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Kids Table -->
                            <div x-show="sizeGenderTab === 'kids'" class="overflow-x-auto border border-[#EADFC8] rounded-2xl shadow-2xs">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-[#FDFBF7] text-[#2B2118] border-b border-[#EADFC8] font-bold text-[11px]">
                                        <tr>
                                            <th class="p-3">الفئة العمرية</th>
                                            <th class="p-3">طول الطفل</th>
                                            <th class="p-3">محيط الصدر</th>
                                            <th class="p-3">محيط الخصر</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#EADFC8] text-[#382416]">
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">2 - 3 سنوات</td>
                                            <td class="p-3 tabular-nums">92 - 98 سم</td>
                                            <td class="p-3 tabular-nums">54 - 56 سم</td>
                                            <td class="p-3 tabular-nums">50 - 52 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">4 - 5 سنوات</td>
                                            <td class="p-3 tabular-nums">104 - 110 سم</td>
                                            <td class="p-3 tabular-nums">58 - 60 سم</td>
                                            <td class="p-3 tabular-nums">53 - 55 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">6 - 7 سنوات</td>
                                            <td class="p-3 tabular-nums">116 - 122 سم</td>
                                            <td class="p-3 tabular-nums">62 - 64 سم</td>
                                            <td class="p-3 tabular-nums">56 - 58 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors bg-[#FDFBF7]/50">
                                            <td class="p-3 font-bold text-[#A9552F]">8 - 10 سنوات</td>
                                            <td class="p-3 tabular-nums">128 - 140 سم</td>
                                            <td class="p-3 tabular-nums">66 - 71 سم</td>
                                            <td class="p-3 tabular-nums">59 - 62 سم</td>
                                        </tr>
                                        <tr class="hover:bg-[#F3E9D2]/20 transition-colors">
                                            <td class="p-3 font-bold text-[#A9552F]">12 - 14 سنة</td>
                                            <td class="p-3 tabular-nums">152 - 164 سم</td>
                                            <td class="p-3 tabular-nums">76 - 82 سم</td>
                                            <td class="p-3 tabular-nums">65 - 69 سم</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: Fit Recommender Calculator -->
                        <div x-show="sizeGuideTab === 'calculator'" class="space-y-6">
                            <div class="bg-[#FDFBF7] p-5 rounded-2xl border border-[#EADFC8] space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Height -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-[#2B2118]">طولك التقريبي:</span>
                                            <span class="font-mono font-bold text-[#A9552F] tabular-nums" x-text="userHeight + ' سم'"></span>
                                        </div>
                                        <input type="range" min="145" max="205" step="1" x-model="userHeight" 
                                               class="w-full accent-[#AC9653] cursor-pointer">
                                        <div class="flex justify-between text-[10px] text-[#8F7B63]">
                                            <span>145 سم</span>
                                            <span>175 سم</span>
                                            <span>205 سم</span>
                                        </div>
                                    </div>

                                    <!-- Weight -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-[#2B2118]">وزنك التقريبي:</span>
                                            <span class="font-mono font-bold text-[#A9552F] tabular-nums" x-text="userWeight + ' كجم'"></span>
                                        </div>
                                        <input type="range" min="45" max="135" step="1" x-model="userWeight" 
                                               class="w-full accent-[#AC9653] cursor-pointer">
                                        <div class="flex justify-between text-[10px] text-[#8F7B63]">
                                            <span>45 كجم</span>
                                            <span>85 كجم</span>
                                            <span>135 كجم</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fit Style Selection -->
                                <div class="space-y-2 pt-2 border-t border-[#EADFC8]">
                                    <span class="block text-xs font-bold text-[#2B2118]">كيف تفضل مقاس القطعة عليك؟</span>
                                    <div class="grid grid-cols-3 gap-2 text-xs">
                                        <button type="button" 
                                                @click="userFitPreference = 'slim'" 
                                                :class="userFitPreference === 'slim' ? 'bg-[#382416] text-white font-bold' : 'bg-white border border-[#EADFC8] text-[#6B5642]'"
                                                class="py-2 px-3 rounded-xl transition-all cursor-pointer text-center">
                                            مضبوط ضيق (Slim)
                                        </button>
                                        <button type="button" 
                                                @click="userFitPreference = 'regular'" 
                                                :class="userFitPreference === 'regular' ? 'bg-[#382416] text-white font-bold' : 'bg-white border border-[#EADFC8] text-[#6B5642]'"
                                                class="py-2 px-3 rounded-xl transition-all cursor-pointer text-center">
                                            قياسي مريح (Regular)
                                        </button>
                                        <button type="button" 
                                                @click="userFitPreference = 'loose'" 
                                                :class="userFitPreference === 'loose' ? 'bg-[#382416] text-white font-bold' : 'bg-white border border-[#EADFC8] text-[#6B5642]'"
                                                class="py-2 px-3 rounded-xl transition-all cursor-pointer text-center">
                                            واسع وفضفاض (Loose)
                                        </button>
                                    </div>
                                </div>

                                <!-- Dynamic Fit Result -->
                                <div class="p-5 rounded-2xl bg-[#AC9653]/15 border border-[#AC9653]/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="space-y-1 text-center sm:text-right">
                                        <span class="text-xs text-[#8F7B63] font-bold block">المقاس المقترح والمناسب لك هو:</span>
                                        <div class="flex items-center gap-2 justify-center sm:justify-start">
                                            <span class="text-2xl sm:text-3xl font-black text-[#382416]" x-text="recommendedSize"></span>
                                            <span class="text-xs text-emerald-800 font-bold bg-emerald-100 px-2.5 py-0.5 rounded-full">نسبة ملائمة 95%</span>
                                        </div>
                                        <p class="text-[11px] text-[#6B5642]">
                                            المقاس تم تقديره وفق جداول النماذج القياسية لـ {{ settings('site_name', 'أزياء لافيستا') }}.
                                        </p>
                                    </div>

                                    <button type="button" 
                                            @click="sizeGuideOpen = false" 
                                            class="py-2.5 px-6 rounded-xl bg-[#382416] text-[#FBF6EA] font-bold text-xs hover:bg-[#2B2118] transition-colors shrink-0 cursor-pointer">
                                        اعتماد المقاس واختياره
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: How to Measure Guide -->
                        <div x-show="sizeGuideTab === 'how_to_measure'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                                <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] space-y-1.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#AC9653]/20 text-[#382416] font-bold flex items-center justify-center text-xs">1</div>
                                    <h4 class="font-bold text-[#2B2118]">محيط الصدر (Chest)</h4>
                                    <p class="text-[#6B5642] leading-relaxed text-[11px]">
                                        مرر شريط القياس أفقياً تحت الإبطين وحول الجزء الأكثر امتلاءً من الصدر، مع ترك مسافة إصبع للراحة والتنفس الطبيعي.
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] space-y-1.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#AC9653]/20 text-[#382416] font-bold flex items-center justify-center text-xs">2</div>
                                    <h4 class="font-bold text-[#2B2118]">محيط الخصر (Waist)</h4>
                                    <p class="text-[#6B5642] leading-relaxed text-[11px]">
                                        قس حول خط الخصر الطبيعي (أضيق منطقة فوق السرة بقليل) دون شد الشريط بقوة أو كتم النفس.
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] space-y-1.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#AC9653]/20 text-[#382416] font-bold flex items-center justify-center text-xs">3</div>
                                    <h4 class="font-bold text-[#2B2118]">عرض الأكتاف (Shoulders)</h4>
                                    <p class="text-[#6B5642] leading-relaxed text-[11px]">
                                        قس المسافة المستقيمة عبر الظهر من نقطة التقاء عظمة الكتف الأيمن بالذراع وحتى عظمة الكتف الأيسر.
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] space-y-1.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#AC9653]/20 text-[#382416] font-bold flex items-center justify-center text-xs">4</div>
                                    <h4 class="font-bold text-[#2B2118]">طول الكم (Sleeve Length)</h4>
                                    <p class="text-[#6B5642] leading-relaxed text-[11px]">
                                        قم بالقياس من طرف الكتف الخارجي على طول الذراع حتى عظمة معصم اليد.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="p-4 sm:p-5 bg-[#FDFBF7] border-t border-[#EADFC8] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2 text-[#6B5642] text-[11px]">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>اطمئن: يمكنك معاينة وقياس القطعة مع مندوب التوصيل قبل دفع الحساب</span>
                        </div>

                        <button type="button" 
                                @click="sizeGuideOpen = false" 
                                class="w-full sm:w-auto py-2 px-6 rounded-xl bg-[#AC9653] hover:bg-[#978243] text-white font-bold transition-colors cursor-pointer text-center">
                            إغلاق الدليل
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- Customer Reviews & Ratings Section -->
        <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-10 border border-[#EADFC8] shadow-xs space-y-6"
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
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#EADFC8] pb-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">آراء وتقييمات العملاء</h2>
                    <p class="text-xs text-[#8F7B63] mt-0.5">آراء وتجارب المتسوقين حول جودة وخامة هذه القطعة</p>
                </div>
                
                @php
                    $reviewsCount = $product->reviews->count();
                    $avgRating = $reviewsCount > 0 ? round($product->reviews->avg('rating'), 1) : 0;
                @endphp

                <div class="flex items-center gap-3 bg-[#F3E9D2] px-4 py-2 rounded-2xl border border-[#EADFC8] shrink-0">
                    <div class="text-2xl font-black text-[#382416] tabular-nums">{{ $avgRating > 0 ? $avgRating : 'جديد' }}</div>
                    <div class="text-xs">
                        <div class="flex text-[#CDB78F]">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current text-[#A9552F]' : 'text-[#EADFC8]' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[11px] text-[#8F7B63] font-medium">({{ $reviewsCount }} تقييم)</span>
                    </div>
                </div>
            </div>

            <!-- Reviews List & Submission Form -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: List of Reviews -->
                <div class="lg:col-span-7 space-y-4">
                    @if($product->reviews->isNotEmpty())
                        <div class="divide-y divide-[#EADFC8]">
                            @foreach($product->reviews as $review)
                                <div class="py-3.5 first:pt-0 last:pb-0 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-[#382416] text-[#FBF6EA] font-bold text-xs flex items-center justify-center">
                                                {{ mb_substr($review->user?->name ?? 'ع', 0, 1) }}
                                            </div>
                                            <span class="font-bold text-xs text-[#2B2118]">{{ $review->user?->name ?? 'عميل موثق' }}</span>
                                        </div>
                                        <span class="text-[10px] text-[#8F7B63] font-medium">{{ $review->created_at?->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div class="flex text-[#A9552F]">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= (int)$review->rating ? 'fill-current' : 'text-[#EADFC8]' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>

                                    @if($review->comment)
                                        <p class="text-xs text-[#6B5642] leading-relaxed bg-[#F3E9D2]/30 p-2.5 rounded-xl border border-[#EADFC8]">
                                            {{ $review->comment }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-[#F3E9D2]/20 rounded-2xl border border-[#EADFC8] space-y-2">
                            <span class="text-2xl">✨</span>
                            <p class="text-xs font-bold text-[#2B2118]">كن أول من يقيّم هذا الموديل!</p>
                            <p class="text-[11px] text-[#8F7B63]">رأيك يهمنا ويساعد الآخرين على اختيار المقاس والخامة المناسبة.</p>
                        </div>
                    @endif
                </div>

                <!-- Right: Submit Review Form -->
                <div class="lg:col-span-5 bg-[#F3E9D2]/40 rounded-2xl p-5 border border-[#EADFC8] space-y-4">
                    <h3 class="font-serif font-bold text-xs sm:text-sm text-[#2B2118]">أضف تقييمك ورأيك في القطعة</h3>

                    @auth
                        <form @submit.prevent="submitReview()" class="space-y-3">
                            <div>
                                <label class="font-bold text-[11px] text-[#2B2118] block mb-1">التقييم:</label>
                                <div class="flex items-center gap-1.5">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button" 
                                                @click="rating = star" 
                                                class="text-2xl transition-transform hover:scale-110 cursor-pointer"
                                                :class="star <= rating ? 'text-[#A9552F]' : 'text-[#EADFC8]'">
                                            ★
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label for="reviewComment" class="font-bold text-[11px] text-[#2B2118] block mb-1">تعليقك (اختياري):</label>
                                <textarea id="reviewComment" 
                                          x-model="comment" 
                                          rows="3" 
                                          placeholder="ما رأيك في جودة القماش، المقاس، وتطابق الموديل مع الصورة؟"
                                          class="w-full bg-white border border-[#EADFC8] rounded-xl p-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] transition-colors"></textarea>
                            </div>

                            <button type="submit" 
                                    :disabled="submitting"
                                    class="w-full py-2.5 px-4 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all disabled:opacity-50 cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                                <span x-show="!submitting">إرسال التقييم</span>
                                <span x-show="submitting">جاري الإرسال...</span>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-4 space-y-2">
                            <p class="text-xs text-[#6B5642] font-semibold">يرجى تسجيل الدخول أولاً لتتمكن من إضافة تقييمك للقطعة.</p>
                            <a href="{{ route('login') }}" class="inline-block py-2 px-4 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs transition-colors">
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
                <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                    <div>
                        <span class="text-xs font-bold text-[#A9552F] block">تنسيقات مقترحة</span>
                        <h2 class="text-lg sm:text-2xl font-serif font-black text-[#2B2118]">قطع أخرى قد تنال إعجابك</h2>
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
