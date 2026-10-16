{{-- resources/views/site/wishlist.blade.php --}}
@extends('layouts.storefront')

@section('title', 'قائمة الأزياء المفضلة | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-6 sm:py-10 bg-[#FBF6EA] min-h-[80vh]">
    <div class="container mx-auto px-3 sm:px-4 md:px-6 max-w-7xl space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold text-[#8F7B63] flex-wrap">
            <a href="{{ route('site.home') }}" class="hover:text-[#2B2118] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-black">قائمة الأزياء المفضلة</span>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#F3E9D2] text-[#A9552F] flex items-center justify-center text-base">🤍</span>
                    <h1 class="text-xl sm:text-2xl font-black text-[#2B2118]">قائمة أزيائك المفضلة</h1>
                </div>
                <p class="text-xs sm:text-sm text-[#8F7B63] mt-1">القطع والموديلات التي قمت بحفظها لتنسيقها وطلبها لاحقاً من {{ settings('site_name', 'أزياء لافيستا') }}</p>
            </div>

            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
            <button type="button" 
                    onclick="clearWishlist()"
                    class="self-start sm:self-auto px-4 py-2 rounded-xl border border-[#EADFC8] hover:border-[#A9552F] text-[#A9552F] hover:bg-[#FBF6EA] text-xs font-black transition-colors cursor-pointer flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>إفراغ قائمة المفضلة</span>
            </button>
            @endif
        </div>

        <!-- Wishlist Items Grid -->
        @if(isset($wishlistItems) && $wishlistItems->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
            @foreach($wishlistItems as $item)
                @if($item->product)
                <div class="wishlist-item-wrapper relative bg-white border border-[#EADFC8] hover:border-[#CDB78F] rounded-2xl p-3 flex flex-col justify-between shadow-xs hover:shadow-md transition-all group" 
                     data-product-id="{{ $item->product->id }}">
                    
                    <!-- Remove Button (Top Corner) -->
                    <button type="button" 
                            onclick="removeFromWishlist({{ $item->product->id }})" 
                            class="absolute top-2 start-2 z-20 w-7 h-7 rounded-full bg-[#FBF6EA] hover:bg-[#A9552F] text-[#6B5642] hover:text-white flex items-center justify-center text-xs font-bold transition-all shadow-2xs cursor-pointer border border-[#EADFC8]"
                            title="إزالة من المفضلة">
                        ✕
                    </button>

                    <!-- Product Image -->
                    <div class="relative w-full aspect-square bg-[#FBF6EA]/50 rounded-xl overflow-hidden mb-2.5 flex items-center justify-center p-2">
                        <a href="{{ route('product.details', $item->product->slug ?? $item->product->id) }}" class="block w-full h-full">
                            <img src="{{ $item->product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                 alt="{{ $item->product->name }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </a>
                        @if($item->product->has_discount)
                            <span class="absolute bottom-2 end-2 bg-[#A9552F] text-white font-black text-[10px] px-2 py-0.5 rounded-full shadow-xs">
                                -{{ $item->product->discount_percentage }}%
                            </span>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-1 text-right flex-1 flex flex-col justify-between">
                        <div>
                            @if($item->product->category)
                                <span class="text-[10px] text-[#8F7B63] font-bold block">{{ $item->product->category->name }}</span>
                            @endif
                            <a href="{{ route('product.details', $item->product->slug ?? $item->product->id) }}" class="font-black text-xs sm:text-sm text-[#2B2118] hover:text-[#A9552F] transition-colors line-clamp-2 leading-snug">
                                {{ $item->product->name }}
                            </a>
                        </div>

                        <!-- Price -->
                        <div class="pt-1.5 flex items-baseline gap-1.5 flex-wrap">
                            <span class="font-black text-xs sm:text-sm text-[#A9552F] tabular-nums">
                                {{ format_currency($item->product->final_price) }}
                            </span>
                            @if($item->product->has_discount)
                                <span class="text-[10px] text-[#8F7B63] line-through tabular-nums">
                                    {{ format_currency($item->product->price) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Add to Cart CTA -->
                    <div class="pt-3 mt-2 border-t border-[#EADFC8]">
                        @if($item->product->available_stock > 0)
                            <a href="{{ route('product.details', $item->product->slug ?? $item->product->id) }}" 
                               class="w-full py-2 px-3 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 active:scale-95 cursor-pointer">
                                <span>اختيار المقاس والطلب</span>
                                <span>🛍️</span>
                            </a>
                        @else
                            <button type="button" 
                                    class="w-full py-2 px-3 rounded-xl bg-[#F3E9D2] text-[#8F7B63] text-xs font-bold cursor-not-allowed" 
                                    disabled>
                                نفدت المقاسات
                            </button>
                        @endif
                    </div>

                </div>
                @endif
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-8 sm:p-14 border border-[#EADFC8] text-center space-y-4 shadow-xs max-w-xl mx-auto">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-[#F3E9D2] text-[#A9552F] border border-[#CDB78F] flex items-center justify-center text-4xl shadow-inner">
                🤍
            </div>
            <h3 class="text-base sm:text-lg font-black text-[#2B2118]">قائمة المفضلة فارغة حالياً</h3>
            <p class="text-xs sm:text-sm text-[#8F7B63] max-w-md mx-auto leading-relaxed">
                @if(!auth()->check())
                    قم بتسجيل الدخول لتتمكن من حفظ موديلات الأزياء والتنسيقات المفضلة والرجوع إليها في أي وقت.
                @else
                    لم تقم بإضافة أي موديلات إلى قائمة المفضلة بعد. تصفح تشكيلاتنا الراقية وأضف ما ترغب لشرائه لاحقاً!
                @endif
            </p>
            <div class="pt-2 flex items-center justify-center gap-3 flex-wrap">
                @if(!auth()->check())
                <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all shadow-md">
                    تسجيل الدخول
                </a>
                @endif
                <a href="{{ route('site.products') }}" class="px-6 py-2.5 rounded-xl bg-[#A9552F] hover:bg-[#8e4524] text-white text-xs font-bold transition-all shadow-md">
                    تصفح تشكيلات الأزياء ←
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    function removeFromWishlist(productId) {
        window.tawheedConfirm('حذف من المفضلة', 'هل تود إزالة هذه القطعة من قائمة رغباتك المفضلة؟', function() {
            $.post("{{ route('wishlist.remove', ':id') }}".replace(':id', productId))
            .done(function(res) {
                if (res.success) {
                    toastr.success(res.message || 'تمت الإزالة من المفضلة');
                    window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: res.wishlist_count }));
                    $(`.wishlist-item-wrapper[data-product-id="${productId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('.wishlist-item-wrapper').length === 0) {
                            window.location.reload();
                        }
                    });
                } else {
                    toastr.error(res.message || 'تعذر حذف العنصر');
                }
            })
            .fail(function() {
                toastr.error('تعذر حذف العنصر من المفضلة');
            });
        }, null, { confirmText: 'نعم، إزالة', cancelText: 'إبقاء', icon: '🗑️' });
    }

    function clearWishlist() {
        window.tawheedConfirm('تفريغ المفضلة', 'هل أنت متأكد من رغبتك في حذف كافة القطع من قائمة المفضلة؟', function() {
            $.post("{{ route('wishlist.clear') }}")
            .done(function(res) {
                if (res.success) {
                    toastr.success(res.message || 'تم تفريغ المفضلة بنجاح');
                    window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: 0 }));
                    window.location.reload();
                } else {
                    toastr.error(res.message || 'تعذر إفراغ المفضلة');
                }
            })
            .fail(function() {
                toastr.error('تعذر إفراغ قائمة المفضلة');
            });
        }, null, { confirmText: 'تفريغ الكل', cancelText: 'تراجع', icon: '⚠️' });
    }
</script>
@endpush