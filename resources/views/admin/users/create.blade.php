@extends('layouts.admin')

@section('title', __('إضافة مشرف جديد'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('إضافة مشرف جديد') }}</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('إنشاء حساب وصول جديد للوحة تحكم إدارة متجر التوحيد') }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            <span>&rarr;</span>
            <span>{{ __('العودة لقائمة المشرفين') }}</span>
        </a>
    </div>

    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-900 mb-2">{{ __('اسم المشرف') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all @error('name') border-red-500 @enderror" 
                           value="{{ old('name') }}" required placeholder="مثال: أحمد محمد">
                    @error('name') 
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-900 mb-2">{{ __('البريد الإلكتروني') }} <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" dir="ltr"
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all text-right @error('email') border-red-500 @enderror" 
                           value="{{ old('email') }}" required placeholder="admin@altawhid.com">
                    @error('email') 
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-900 mb-2">{{ __('كلمة المرور') }} <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" dir="ltr"
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all text-right @error('password') border-red-500 @enderror" 
                           required placeholder="••••••••">
                    @error('password') 
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-gray-900 mb-2">{{ __('الدور / الصلاحية') }} <span class="text-red-500">*</span></label>
                    <select id="role" name="role" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all @error('role') border-red-500 @enderror" required>
                        <option value="">-- {{ __('اختر الصلاحية المناسبة') }} --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role') 
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('حفظ المشرف') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection