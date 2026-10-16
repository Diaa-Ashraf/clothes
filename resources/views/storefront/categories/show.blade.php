{{-- resources/views/storefront/categories/show.blade.php --}}
@extends('layouts.storefront')

@section('title', $category->name . ' | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-8 bg-[#FBF6EA] min-h-screen" dir="rtl">
    <div class="container mx-auto px-4 space-y-6">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-[#8F7B63]">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('site.products') }}" class="hover:text-[#382416] transition-colors">أقسام الأزياء</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">{{ $category->name }}</span>
        </nav>

        <!-- Category Hero Banner -->
        <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="space-y-1 text-center sm:text-right">
                <span class="text-xs font-bold text-[#A9552F] block">قسم في {{ settings('site_name', 'أزياء لافيستا') }}</span>
                <h1 class="text-2xl sm:text-3xl font-serif font-black text-[#2B2118]">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-xs sm:text-sm text-[#8F7B63] max-w-xl leading-relaxed">{{ $category->description }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-xs font-bold text-[#382416]">
                    {{ $products->total() }} قطعة متاحة
                </span>
            </div>
        </div>

        <!-- 2 Column Layout (Sidebar Filters + Products Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Filters Sidebar -->
            <aside class="space-y-6">
                <!-- Filters Card -->
                <div class="bg-[#FDFBF7] rounded-2xl p-5 border border-[#EADFC8] shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                        <h3 class="font-serif font-bold text-sm text-[#2B2118]">تصفية التشكيلة</h3>
                        @if(request()->hasAny(['deals_only', 'in_stock', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('category.product', $category->id) }}" class="text-xs text-[#A9552F] hover:underline font-bold">
                                مسح الفلاتر
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('category.product', $category->id) }}" method="GET" class="space-y-4 text-xs">
                        <!-- Quick Checkboxes -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="deals_only" value="1" {{ request('deals_only') ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 rounded text-[#A9552F] focus:ring-[#A9552F] border-[#EADFC8]">
                                <span class="font-bold text-[#2B2118]">عروض وتخفيضات فقط</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 rounded text-[#382416] focus:ring-[#382416] border-[#EADFC8]">
                                <span class="font-bold text-[#2B2118]">متوفر بالمخزون فقط</span>
                            </label>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="pt-3 border-t border-[#EADFC8] space-y-2">
                            <span class="font-bold text-[#2B2118] block">نطاق السعر (ج.م)</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-[#8F7B63] block mb-1">من</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                                           class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] rounded-lg px-2.5 py-1.5 text-xs text-[#2B2118] outline-none">
                                </div>
                                <div>
                                    <label class="text-[10px] text-[#8F7B63] block mb-1">إلى</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="2000"
                                           class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] rounded-lg px-2.5 py-1.5 text-xs text-[#2B2118] outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div class="pt-3 border-t border-[#EADFC8] space-y-2">
                            <span class="font-bold text-[#2B2118] block">الترتيب</span>
                            <select name="sort" onchange="this.form.submit()" class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] rounded-lg px-3 py-2 text-xs text-[#2B2118] outline-none">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث في القسم</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: الأقل للأعلى</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: الأعلى للأقل</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>أبجدياً (أ - ي)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs transition-colors shadow-xs">
                            تطبيق الفلاتر
                        </button>
                    </form>
                </div>

                <!-- Other Categories Navigation -->
                <div class="bg-[#FDFBF7] rounded-2xl p-5 border border-[#EADFC8] shadow-xs space-y-3">
                    <h3 class="font-serif font-bold text-sm text-[#2B2118] border-b border-[#EADFC8] pb-2">أقسام أخرى</h3>
                    <div class="space-y-1 text-xs">
                        @foreach($otherCategories as $otherCat)
                            <a href="{{ route('category.product', $otherCat->id) }}" 
                               class="flex items-center justify-between py-1.5 px-2 rounded-lg transition-colors {{ $otherCat->id == $category->id ? 'bg-[#F3E9D2] text-[#382416] font-bold' : 'text-[#6B5642] hover:bg-[#F3E9D2]/50' }}">
                                <span>{{ $otherCat->name }}</span>
                                <span class="text-[10px] text-[#8F7B63] font-normal">{{ $otherCat->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- Main Products Grid -->
            <main class="lg:col-span-3 space-y-6">
                @if($products->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
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
                    <div class="bg-[#FDFBF7] rounded-3xl p-12 border border-[#EADFC8] text-center space-y-3 shadow-xs">
                        <span class="text-4xl block">🛍️</span>
                        <h3 class="text-base font-serif font-bold text-[#2B2118]">لا توجد قطع مطابقة في هذا القسم حالياً</h3>
                        <p class="text-xs text-[#8F7B63] max-w-sm mx-auto">
                            جرب تغيير خيارات الفلترة أو تصفح باقي أقسام {{ settings('site_name', 'أزياء لافيستا') }}.
                        </p>
                        <a href="{{ route('category.product', $category->id) }}" class="inline-block px-5 py-2.5 rounded-xl bg-[#382416] text-[#FBF6EA] font-bold text-xs hover:bg-[#2B2118]">
                            عرض كل قطع القسم
                        </a>
                    </div>
                @endif
            </main>

        </div>

    </div>
</div>
@endsection
