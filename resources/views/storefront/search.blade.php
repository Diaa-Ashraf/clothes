{{-- resources/views/storefront/search.blade.php --}}
@extends('layouts.storefront')

@section('title', 'نتائج البحث عن: ' . ($queryText ?: 'كافة الملابس') . ' | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-8 bg-gray-50 min-h-screen" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- مسار التنقل -->
        <nav class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('site.home') }}" class="hover:text-[#AC9653] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">البحث في المتجر</span>
        </nav>

        <!-- صندوق البحث الرئيسي -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-2xs max-w-3xl mx-auto space-y-4 text-center">
            <span class="text-xs font-bold text-[#AC9653] tracking-wider uppercase block">{{ settings('site_name', 'أزياء لافيستا') }} • محرك البحث السريع</span>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900">
                @if($queryText)
                    نتائج البحث عن: <span class="text-[#AC9653]">"{{ $queryText }}"</span>
                @else
                    البحث في متجر {{ settings('site_name', 'أزياء لافيستا') }} للملابس
                @endif
            </h1>

            <!-- نموذج البحث -->
            <form action="{{ route('search.index') }}" method="GET" class="relative max-w-xl mx-auto">
                <input type="text" 
                       name="q" 
                       value="{{ $queryText }}"
                       placeholder="ابحث عن معطف، قميص حرير، بليزر كتان، فستان..." 
                       class="w-full bg-white border border-[#BDAB75]/40 focus:border-[#AC9653] py-3.5 pr-12 pl-4 text-xs sm:text-sm text-[#2B2118] outline-none transition-colors">
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#AC9653] hover:text-[#CBA367]" aria-label="بحث">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <p class="text-xs text-[#8F7B63]">
                تم العثور على <strong class="text-[#2B2118]">{{ $products->total() }}</strong> قطعة متطابقة في التشكيلة
            </p>
        </div>

        <!-- شبكة النتائج -->
        @if($products->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <x-storefront.ui.product-card :product="$product" />
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-[#FDFBF7] p-12 border border-[#BDAB75]/30 text-center space-y-3 max-w-md mx-auto">
                <span class="text-4xl block">✨</span>
                <h3 class="text-base font-serif-luxury font-bold text-[#2B2118]">لم نجد أي قطع مطابقة لبحثك</h3>
                <p class="text-xs text-[#8F7B63] leading-relaxed">
                    تأكد من كتابة الكلمات بشكل صحيح، أو تصفح التشكيلات الحصرية في الأتيليه.
                </p>
                <div class="pt-2">
                    <a href="{{ route('site.products') }}" class="inline-block px-7 py-3 bg-[#AC9653] text-white font-bold text-xs shadow-xs hover:bg-[#917C3F] transition-colors">
                        تصفح كافة التشكيلات
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
