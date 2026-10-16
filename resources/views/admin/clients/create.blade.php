@extends('layouts.admin')

@section('title', 'إضافة عميل جديد')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.clients.index') }}" class="hover:text-[#2B2118] transition-colors">العملاء</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">إضافة عميل</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">إضافة عميل جديد</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تسجيل عميل جديد لحساب متجر الأزياء وتوصيل الطلبات</p>
        </div>
        <a href="{{ route('admin.clients.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للقائمة</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.clients.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold text-[#2B2118]">
                        اسم العميل الكامل <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: ياسمين أحمد"
                           class="w-full px-4 py-2.5 rounded-xl border @error('name') border-[#A9552F] bg-red-50/20 @else border-[#EADFC8] @enderror focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all">
                    @error('name')
                        <p class="text-xs text-[#A9552F] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-1.5">
                    <label for="phone" class="block text-xs font-bold text-[#2B2118]">
                        رقم الهاتف للتواصل والشحن <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                           placeholder="01xxxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl border @error('phone') border-[#A9552F] bg-red-50/20 @else border-[#EADFC8] @enderror focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                    @error('phone')
                        <p class="text-xs text-[#A9552F] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-[#2B2118]">
                        البريد الإلكتروني <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="client@example.com"
                           class="w-full px-4 py-2.5 rounded-xl border @error('email') border-[#A9552F] bg-red-50/20 @else border-[#EADFC8] @enderror focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                    @error('email')
                        <p class="text-xs text-[#A9552F] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-[#2B2118]">
                        كلمة مرور الحساب <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="password" id="password" name="password" required minlength="6"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border @error('password') border-[#A9552F] bg-red-50/20 @else border-[#EADFC8] @enderror focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all">
                    @error('password')
                        <p class="text-xs text-[#A9552F] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2 space-y-1.5">
                    <label for="address" class="block text-xs font-bold text-[#2B2118]">
                        العنوان التفصيلي للشحن <span class="text-[#A9552F]">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3" required
                              placeholder="المدينة، المنطقة، اسم الشارع، رقم العقار والشقة وملاحظات التوصيل..."
                              class="w-full px-4 py-2.5 rounded-xl border @error('address') border-[#A9552F] bg-red-50/20 @else border-[#EADFC8] @enderror focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-xs text-[#A9552F] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EADFC8]">
                <a href="{{ route('admin.clients.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                    حفظ العميل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
