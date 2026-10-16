{{-- resources/views/storefront/categories/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'جميع أقسام وتصنيفات الأزياء | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-8 bg-[#FBF6EA] min-h-screen" dir="rtl">
    <div class="container mx-auto px-4 space-y-6">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-[#8F7B63]">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">أقسام وتصنيفات الأزياء</span>
        </nav>

        <!-- Header -->
        <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-10 border border-[#EADFC8] shadow-xs text-center max-w-2xl mx-auto space-y-2">
            <h1 class="text-2xl sm:text-3xl font-serif font-black text-[#2B2118]">
                أقسام وتصنيفات {{ settings('site_name', 'أزياء لافيستا') }}
            </h1>
            <p class="text-xs sm:text-sm text-[#8F7B63] leading-relaxed">
                تصفح تشكيلاتنا المتنوعة من الأزياء الرجالية، النسائية، ملابس الأطفال، الإكسسوارات، وأحدث صيحات الموضة.
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
            @forelse($categories as $category)
            <a href="{{ route('category.product', $category->id) }}" 
               class="group bg-[#FDFBF7] rounded-3xl p-5 border border-[#EADFC8] hover:border-[#CDB78F] hover:shadow-md transition-all duration-300 flex flex-col items-center text-center hover:-translate-y-1">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#F3E9D2] border border-[#EADFC8] group-hover:border-[#CDB78F] overflow-hidden p-2.5 flex items-center justify-center transition-colors mb-3">
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    @else
                        <svg class="w-8 h-8 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    @endif
                </div>
                <h3 class="font-serif font-bold text-sm sm:text-base text-[#2B2118] group-hover:text-[#A9552F] transition-colors line-clamp-1 mb-1">
                    {{ $category->name }}
                </h3>
                <span class="text-xs text-[#8F7B63] font-medium">
                    {{ $category->products_count }} قطعة متاحة
                </span>
            </a>
            @empty
            <div class="col-span-full py-16 text-center text-[#8F7B63]">
                <p class="font-bold text-base">لا توجد أقسام مسجلة حالياً.</p>
            </div>
            @endforelse
        </div>

        @if($categories->hasPages())
            <div class="pt-6 flex justify-center">
                {{ $categories->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
