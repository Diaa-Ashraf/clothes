{{-- resources/views/storefront/categories/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'جميع أقسام المتجر | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-canvas-base min-h-screen">
 <div class="container mx-auto px-4 space-y-6">
 
 <!-- Breadcrumb -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">أقسام المتجر</span>
 </nav>

 <!-- Header -->
 <div class="bg-white rounded-3xl p-6 sm:p-10 border border-cream shadow-sm text-center max-w-2xl mx-auto space-y-2">
 <span class="inline-block text-3xl mb-1"></span>
 <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">
 أقسام ألبان وسوبرماركت التوحيد
 </h1>
 <p class="text-xs sm:text-sm text-charcoal-muted leading-relaxed">
 تصفح أقسامنا المتنوعة من الألبان البلدية الطبيعية، الجبن، البقالة والتموين، والمنظفات وكل مستلزمات منزلك بالعامرية.
 </p>
 </div>

 <!-- Categories Grid -->
 <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
 @forelse($categories as $category)
 <a href="{{ route('category.product', $category->id) }}" 
 class="group bg-white rounded-3xl p-5 border border-cream hover:border-primary-400 hover:shadow-card-hover transition-all duration-300 flex flex-col items-center text-center">
 <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-canvas-base border-2 border-cream group-hover:border-primary-500 overflow-hidden p-2.5 flex items-center justify-center transition-colors mb-3">
 @if($category->image_url)
 <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
 @else
 <span class="text-3xl sm:text-4xl"></span>
 @endif
 </div>
 <h3 class="font-bold text-sm sm:text-base text-charcoal-heading group-hover:text-primary-800 transition-colors line-clamp-1 mb-1">
 {{ $category->name }}
 </h3>
 <span class="text-xs text-accent-700 font-semibold">
 {{ $category->products_count }} منتج متاح
 </span>
 </a>
 @empty
 <div class="col-span-full py-16 text-center text-charcoal-muted">
 <span class="text-4xl block mb-2"></span>
 <p class="font-bold text-base">لا توجد أقسام مسجلة حالياً.</p>
 </div>
 @endforelse
 </div>

 @if($categories->hasPages())
 <div class="pt-6">
 {{ $categories->links() }}
 </div>
 @endif

 </div>
</div>
@endsection
