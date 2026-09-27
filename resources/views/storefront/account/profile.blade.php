{{-- resources/views/storefront/account/profile.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'الملف الشخصي | ألبان وسوبرماركت التوحيد')
@section('page_title', 'الملف الشخصي')

@section('account_content')
<div class="space-y-6">
    
    <!-- 1. Profile Information Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm space-y-5">
        <div class="border-b border-cream pb-3">
            <h1 class="text-base sm:text-lg font-black text-charcoal-heading">تعديل البيانات الأساسية</h1>
            <p class="text-xs text-charcoal-muted">تحديث الاسم ورقم الهاتف والبريد الإلكتروني لحسابك</p>
        </div>

        <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="font-bold text-charcoal-heading block mb-1.5">
                    الاسم بالكامل <span class="text-rose-600">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                @error('name')
                    <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone & Email Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="font-bold text-charcoal-heading block mb-1.5">
                        رقم الهاتف <span class="text-rose-600">*</span>
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           required 
                           dir="ltr"
                           class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                    @error('phone')
                        <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="font-bold text-charcoal-heading block mb-1.5">
                        البريد الإلكتروني <span class="text-rose-600">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           dir="ltr"
                           class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                    @error('email')
                        <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="py-2.5 px-6 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Change Password Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm space-y-5">
        <div class="border-b border-cream pb-3">
            <h2 class="text-base sm:text-lg font-black text-charcoal-heading">تغيير كلمة المرور</h2>
            <p class="text-xs text-charcoal-muted">تحديث كلمة مرور حسابك لزيادة الأمان</p>
        </div>

        <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="font-bold text-charcoal-heading block mb-1.5">
                    كلمة المرور الحالية <span class="text-rose-600">*</span>
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       required 
                       class="w-full sm:max-w-md bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                @error('current_password')
                    <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password & Confirmation Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="font-bold text-charcoal-heading block mb-1.5">
                        كلمة المرور الجديدة <span class="text-rose-600">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                    @error('password')
                        <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="font-bold text-charcoal-heading block mb-1.5">
                        تأكيد كلمة المرور الجديدة <span class="text-rose-600">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="py-2.5 px-6 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                    تحديث كلمة المرور
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
