@extends('layouts.site')

@section('content')
<div class="bg-[#F8F9FA] min-h-[80vh] py-8 sm:py-12 text-[#18181B]">
 <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
 
 {{-- Header Breadcrumb & Title --}}
 <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 mb-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <nav class="text-xs text-[#71717A] flex items-center gap-2 mb-1.5">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors">{{ __('الرئيسية') }}</a>
 <span>/</span>
 <span class="text-[#18181B] font-semibold">{{ __('المفضلة') }}</span>
 </nav>
 <h1 class="text-xl sm:text-2xl font-bold text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('قائمة المقتنيات المفضلة') }}</span>
 </h1>
 </div>

 @if(isset($wishlistItems) && $wishlistItems->count() > 0)
 <button type="button" 
 onclick="clearWishlist()"
 class="self-start sm:self-auto px-4 py-2 rounded-full border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold transition-colors">
 {{ __('إفراغ قائمة المفضلة') }}
 </button>
 @endif
 </div>

 {{-- Wishlist Items Grid --}}
 @if(isset($wishlistItems) && $wishlistItems->count() > 0)
 <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
 @foreach($wishlistItems as $item)
 @if($item->product)
 <div class="wishlist-item-wrapper relative" data-product-id="{{ $item->product->id }}">
 <x-product-card :product="$item->product" />
 <button type="button" 
 onclick="removeFromWishlist({{ $item->product->id }})" 
 class="absolute top-2 start-2 z-20 w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold shadow-md hover:bg-red-700 transition-transform hover:scale-110"
 title="{{ __('حذف من المفضلة') }}">
 
 </button>
 </div>
 @endif
 @endforeach
 </div>
 @else
 <div class="bg-white border border-[#E5E7EB] rounded-3xl p-12 sm:p-16 text-center space-y-4 shadow-xs max-w-xl mx-auto">
 <div class="w-20 h-20 mx-auto rounded-full bg-[#FAF8F5] border border-[#EADBCC] flex items-center justify-center text-4xl shadow-inner">
 
 </div>
 <h3 class="text-lg font-bold text-[#18181B]">{{ __('قائمة المفضلة فارغة') }}</h3>
 <p class="text-xs text-[#71717A] max-w-sm mx-auto leading-relaxed">
 @if(!auth()->check())
 {{ __('قم بتسجيل الدخول لتتمكن من حفظ مقتنياتك المفضلة والرجوع إليها في أي وقت.') }}
 @else
 {{ __('لم تقم بإضافة أي منتجات إلى قائمة المفضلة بعد. تصفح متجرنا واختر مقتنياتك الفاخرة.') }}
 @endif
 </p>
 <div class="pt-2 flex items-center justify-center gap-3">
 @if(!auth()->check())
 <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-[#C5A059] hover:bg-[#b38e44] text-white text-xs font-bold transition-all shadow-xs">
 {{ __('تسجيل الدخول') }}
 </a>
 @endif
 <a href="{{ route('product.shop') }}" class="px-6 py-2.5 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white text-xs font-bold transition-all shadow-xs">
 {{ __('استكشف المتجر') }} &larr;
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
 window.showLuxuryConfirm({
 title: "{{ __('إزالة من المفضلة') }}",
 message: "{{ __('هل تود إزالة هذا المنتج من قائمة المقتنيات المفضلة؟') }}",
 confirmText: "{{ __('نعم، إزالة') }}",
 cancelText: "{{ __('إبقاء') }}",
 icon: "",
 iconBg: "bg-rose-50 text-rose-600 border-rose-200",
 confirmBtnClass: "bg-rose-600 hover:bg-rose-700 text-white",
 onConfirm: function() {
 $.post("{{ route('wishlist.remove', ':id') }}".replace(':id', productId))
 .done(function(res) {
 if (res.success) {
 toastr.success(res.message);
 window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: res.wishlist_count }));
 $(`.wishlist-item-wrapper[data-product-id="${productId}"]`).fadeOut(300, function() {
 $(this).remove();
 if ($('.wishlist-item-wrapper').length === 0) {
 window.location.reload();
 }
 });
 } else {
 toastr.error(res.message);
 }
 })
 .fail(function() {
 toastr.error("{{ __('تعذر حذف العنصر.') }}");
 });
 }
 });
 }

 function clearWishlist() {
 window.showLuxuryConfirm({
 title: "{{ __('تفريغ قائمة المفضلة') }}",
 message: "{{ __('هل أنت متأكد من رغبتك في إفراغ كافة العناصر من قائمة المفضلة؟') }}",
 confirmText: "{{ __('نعم، تفريغ الكل') }}",
 cancelText: "{{ __('تراجع') }}",
 icon: "",
 iconBg: "bg-amber-50 text-amber-600 border-amber-200",
 confirmBtnClass: "bg-[#18181B] hover:bg-rose-600 text-white",
 onConfirm: function() {
 $.post("{{ route('wishlist.clear') }}")
 .done(function(res) {
 if (res.success) {
 toastr.success(res.message);
 window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: 0 }));
 window.location.reload();
 } else {
 toastr.error(res.message);
 }
 })
 .fail(function() {
 toastr.error("{{ __('تعذر إفراغ المفضلة.') }}");
 });
 }
 });
 }
</script>
@endpush