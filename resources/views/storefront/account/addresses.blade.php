{{-- resources/views/storefront/account/addresses.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'العناوين المحفوظة | ألبان وسوبرماركت التوحيد')
@section('page_title', 'العناوين المحفوظة')

@section('account_content')
<div class="space-y-6" x-data="{ newAddressOpen: false }">
 
 <!-- Addresses Header & Add Button -->
 <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <h1 class="text-base sm:text-lg font-black text-charcoal-heading">عناوين التوصيل بالعامرية</h1>
 <p class="text-xs text-charcoal-muted">العناوين المحفوظة لتسهيل وسرعة توصيل طلباتك القادمة</p>
 </div>

 <button type="button" 
 @click="newAddressOpen = !newAddressOpen"
 class="inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
 <span class="text-sm">+</span>
 <span>إضافة عنوان جديد</span>
 </button>
 </div>

 <!-- New Address Form (Collapsible) -->
 <div x-show="newAddressOpen" x-cloak x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-primary-200 shadow-md space-y-4">
 <div class="border-b border-cream pb-3">
 <h2 class="text-sm font-bold text-charcoal-heading">إضافة عنوان توصيل جديد</h2>
 </div>

 <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
 @csrf

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <div>
 <label for="title" class="font-bold text-charcoal-heading block mb-1">
 تسمية العنوان <span class="text-rose-600">*</span>
 </label>
 <input type="text" 
 id="title" 
 name="title" 
 placeholder="مثال: المنزل، العمل، بيت العائلة" 
 required
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600">
 </div>

 <div class="flex items-center pt-6">
 <label class="flex items-center gap-2 cursor-pointer font-bold text-charcoal">
 <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-primary-800 border-cream">
 <span>تعيين كعنوان افتراضي للتوصيل</span>
 </label>
 </div>
 </div>

 <div>
 <label for="address_text" class="font-bold text-charcoal-heading block mb-1">
 تفاصيل العنوان بالعامرية <span class="text-rose-600">*</span>
 </label>
 <textarea id="address_text" 
 name="address" 
 rows="2" 
 placeholder="المنطقة، اسم الشارع، رقم العمارة والشقة، علامة مميزة..."
 required
 class="w-full bg-canvas-base border border-cream rounded-xl p-3 text-xs text-charcoal outline-none focus:border-primary-600 leading-relaxed"></textarea>
 </div>

 <div class="flex items-center gap-2 pt-2">
 <button type="submit" 
 class="py-2.5 px-6 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
 حفظ العنوان
 </button>
 <button type="button" 
 @click="newAddressOpen = false"
 class="py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-charcoal font-bold text-xs transition-colors cursor-pointer">
 إلغاء
 </button>
 </div>
 </form>
 </div>

 <!-- Saved Addresses List -->
 @if(!empty($addresses))
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 @foreach($addresses as $index => $address)
 <div class="bg-white rounded-3xl p-5 border {{ !empty($address['is_default']) ? 'border-primary-500 shadow-md ring-1 ring-primary-500/20' : 'border-cream shadow-sm' }} space-y-3 flex flex-col justify-between">
 
 <div>
 <div class="flex items-center justify-between gap-2 mb-2">
 <span class="font-black text-sm text-charcoal-heading flex items-center gap-1.5">
 <span></span>
 <span>{{ $address['title'] ?? 'عنوان' }}</span>
 </span>

 @if(!empty($address['is_default']))
 <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary-100 text-primary-800">
 الافتراضي ⭐
 </span>
 @endif
 </div>

 <p class="text-xs text-charcoal-muted leading-relaxed">
 {{ $address['address'] ?? '' }}
 </p>
 </div>

 <!-- Actions -->
 <div class="pt-3 border-t border-cream flex items-center justify-between text-xs">
 @if(empty($address['is_default']))
 <form action="{{ route('account.addresses.default', $index) }}" method="POST">
 @csrf
 @method('PATCH')
 <button type="submit" class="text-primary-800 font-bold hover:underline cursor-pointer">
 جعله الافتراضي
 </button>
 </form>
 @else
 <span class="text-emerald-700 font-bold text-[11px]">العنوان المعتمد للتوصيل</span>
 @endif

 <form action="{{ route('account.addresses.destroy', $index) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا العنوان؟');">
 @csrf
 @method('DELETE')
 <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold cursor-pointer">
 حذف
 </button>
 </form>
 </div>

 </div>
 @endforeach
 </div>
 @else
 <div class="bg-white rounded-3xl p-12 border border-cream text-center space-y-3 shadow-sm">
 <span class="text-4xl block"></span>
 <h3 class="text-base font-bold text-charcoal-heading">لا توجد عناوين محفوظة حتى الآن</h3>
 <p class="text-xs text-charcoal-muted max-w-sm mx-auto">
 أضف عنوان منزلك أو مكان عملك بالعامرية ليتم ملؤه تلقائياً عند تأكيد أي طلب قادم.
 </p>
 <div class="pt-2">
 <button type="button" 
 @click="newAddressOpen = true"
 class="py-2.5 px-5 rounded-xl bg-primary-800 text-white font-bold text-xs">
 إضافة عنوانك الأول
 </button>
 </div>
 </div>
 @endif

</div>
@endsection
