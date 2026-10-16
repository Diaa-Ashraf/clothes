{{-- resources/views/storefront/account/addresses.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'العناوين المحفوظة | ' . settings('site_name', 'أزياء لافيستا'))
@section('page_title', 'العناوين المحفوظة')

@section('account_content')
<div class="space-y-6" x-data="{ newAddressOpen: false }">
    
    <!-- Addresses Header & Add Button -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-base sm:text-lg font-black text-[#2B2118]">عناوين شحن وتوصيل الأزياء</h1>
            <p class="text-xs text-[#8F7B63]">العناوين المحفوظة لتسهيل وسرعة استلام طلبياتك القادمة</p>
        </div>

        <button type="button" 
                @click="newAddressOpen = !newAddressOpen"
                class="inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs shadow-sm transition-all cursor-pointer">
            <span class="text-sm">+</span>
            <span>إضافة عنوان جديد</span>
        </button>
    </div>

    <!-- New Address Form (Collapsible) -->
    <div x-show="newAddressOpen" x-cloak x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-[#CDB78F] shadow-md space-y-4">
        <div class="border-b border-[#EADFC8] pb-3">
            <h2 class="text-sm font-bold text-[#2B2118]">إضافة عنوان شحن جديد</h2>
        </div>

        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="font-bold text-[#2B2118] block mb-1">
                        تسمية العنوان <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           placeholder="مثال: المنزل، العمل، بوتيك الاستلام" 
                           required
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416]">
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-[#2B2118]">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-[#382416] border-[#EADFC8] accent-[#382416]">
                        <span>تعيين كعنوان افتراضي للشحن</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="address_text" class="font-bold text-[#2B2118] block mb-1">
                    تفاصيل العنوان <span class="text-[#A9552F]">*</span>
                </label>
                <textarea id="address_text" 
                          name="address" 
                          rows="2" 
                          placeholder="المدينة، الحي، اسم الشارع، رقم العمارة والشقة، علامة مميزة..." 
                          required
                          class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl p-3 text-xs text-[#2B2118] outline-none focus:border-[#382416] leading-relaxed"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="submit" 
                        class="py-2.5 px-6 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs shadow-sm transition-all cursor-pointer">
                    حفظ العنوان
                </button>
                <button type="button" 
                        @click="newAddressOpen = false"
                        class="py-2.5 px-4 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#2B2118] font-bold text-xs transition-colors cursor-pointer">
                    إلغاء
                </button>
            </div>
        </form>
    </div>

    <!-- Saved Addresses List -->
    @if(!empty($addresses))
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($addresses as $index => $address)
        <div class="bg-white rounded-3xl p-5 border {{ !empty($address['is_default']) ? 'border-[#CDB78F] shadow-md ring-1 ring-[#CDB78F]/30' : 'border-[#EADFC8] shadow-sm' }} space-y-3 flex flex-col justify-between">
            
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="font-black text-sm text-[#2B2118] flex items-center gap-1.5">
                        <span>📍</span>
                        <span>{{ $address['title'] ?? 'عنوان' }}</span>
                    </span>

                    @if(!empty($address['is_default']))
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F3E9D2] text-[#6B5642]">
                        الافتراضي ⭐
                    </span>
                    @endif
                </div>

                <p class="text-xs text-[#6B5642] leading-relaxed">
                    {{ $address['address'] ?? '' }}
                </p>
            </div>

            <!-- Actions -->
            <div class="pt-3 border-t border-[#EADFC8] flex items-center justify-between text-xs">
                @if(empty($address['is_default']))
                <form action="{{ route('account.addresses.default', $index) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-[#382416] font-bold hover:underline cursor-pointer">
                        جعله الافتراضي
                    </button>
                </form>
                @else
                <span class="text-[#A9552F] font-bold text-[11px]">العنوان المعتمد للشحن</span>
                @endif

                <form action="{{ route('account.addresses.destroy', $index) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا العنوان؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[#A9552F] hover:text-[#8e4524] font-semibold cursor-pointer">
                        حذف
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl p-12 border border-[#EADFC8] text-center space-y-3 shadow-sm">
        <span class="text-4xl block">📍</span>
        <h3 class="text-base font-bold text-[#2B2118]">لا توجد عناوين محفوظة حتى الآن</h3>
        <p class="text-xs text-[#8F7B63] max-w-sm mx-auto">
            أضف عنوان منزلك أو مكان عملك ليتم ملؤه تلقائياً عند طلب أي قطعة أزياء قادمة.
        </p>
        <div class="pt-2">
            <button type="button" 
                    @click="newAddressOpen = true"
                    class="py-2.5 px-5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs">
                إضافة عنوانك الأول
            </button>
        </div>
    </div>
    @endif

</div>
@endsection
