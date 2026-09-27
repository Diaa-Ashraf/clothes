{{-- resources/views/storefront/search.blade.php --}}
@extends('layouts.storefront')

@section('title', 'نتائج البحث عن: ' . ($queryText ?: 'كل المنتجات') . ' | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-canvas-base min-h-screen">
 <div class="container mx-auto px-4 space-y-6">
 
 <!-- Breadcrumb -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">نتائج البحث</span>
 </nav>

 <!-- Search Header Box -->
 <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm max-w-3xl mx-auto space-y-4 text-center">
 <h1 class="text-xl sm:text-2xl font-black text-charcoal-heading">
 @if($queryText)
 نتائج البحث عن: <span class="text-primary-800">"{{ $queryText }}"</span>
 @else
 البحث في منتجات ألبان وسوبرماركت التوحيد
 @endif
 </h1>

 <!-- Inline Search Form -->
 <form action="{{ route('search.index') }}" method="GET" class="relative max-w-xl mx-auto">
 <input type="text" 
 name="q" 
 value="{{ $queryText }}"
 placeholder="ابحث عن ألبان، جبن، بقالة، سلع غذائية..." 
 class="w-full bg-canvas-base border border-cream focus:border-primary-600 focus:ring-2 focus:ring-primary-100 rounded-full py-3 pr-12 pl-4 text-xs sm:text-sm text-charcoal outline-none">
 <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-charcoal-muted hover:text-primary-700" aria-label="بحث">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
 </svg>
 </button>
 </form>

 <p class="text-xs text-charcoal-muted">
 تم العثور على <strong class="text-primary-800">{{ $products->total() }}</strong> منتج
 </p>
 </div>

 <!-- Results Grid -->
 @if($products->isNotEmpty())
 <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5">
 @foreach($products as $product)
 <x-storefront.ui.product-card :product="$product" />
 @endforeach
 </div>

 @if($products->hasPages())
 <div class="pt-6">
 {{ $products->links() }}
 </div>
 @endif
 @else
 <div class="bg-white rounded-3xl p-12 border border-cream text-center space-y-3 shadow-sm max-w-md mx-auto">
 <span class="text-5xl block"></span>
 <h3 class="text-base font-bold text-charcoal-heading">لم نجد أي نتائج مطابقة لبحثك</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 تأكد من كتابة الكلمات بشكل صحيح، أو تصفح الأقسام الرئيسية مباشرة.
 </p>
 <div class="pt-2">
 <a href="{{ route('site.products') }}" class="inline-block px-5 py-2.5 rounded-xl bg-primary-800 text-white font-bold text-xs">
 تصفح كل المنتجات
 </a>
 </div>
 </div>
 @endif

 </div>
</div>
@endsection
