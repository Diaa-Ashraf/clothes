@extends('layouts.admin')

@section('title', __('إضافة فرع جديد'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('إضافة فرع جديد') }}</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('أدخل بيانات الفرع الجديد ومواعيد العمل وأرقام التواصل بمنطقة العامرية') }}</p>
        </div>
        <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            <span>&rarr;</span>
            <span>{{ __('العودة لقائمة الفروع') }}</span>
        </a>
    </div>

    <!-- Create Form -->
    <form action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        @csrf

        @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1">
                <p>يرجى تصحيح الأخطاء التالية:</p>
                <ul class="list-disc list-inside font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Branch Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-gray-900">
                    {{ __('اسم الفرع') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="مثال: فرع كينج مريوط"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
            </div>

            <!-- Slug -->
            <div class="space-y-1.5">
                <label for="slug" class="block text-xs font-bold text-gray-900">
                    {{ __('الرابط الدائم (Slug)') }} <span class="text-[10px] text-gray-400 font-normal">({{ __('اختياري - يولد تلقائياً') }})</span>
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" dir="ltr"
                       placeholder="king-maryout"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all text-right">
            </div>

            <!-- Phone -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-gray-900">
                    {{ __('رقم الهاتف') }}
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" dir="ltr"
                       placeholder="01000000001"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all text-right">
            </div>

            <!-- WhatsApp -->
            <div class="space-y-1.5">
                <label for="whatsapp" class="block text-xs font-bold text-gray-900">
                    {{ __('رقم الواتساب') }}
                </label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" dir="ltr"
                       placeholder="201000000001"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all text-right">
            </div>

            <!-- Address (Full Width) -->
            <div class="md:col-span-2 space-y-1.5">
                <label for="address" class="block text-xs font-bold text-gray-900">
                    {{ __('العنوان التفصيلي للفرع') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                       placeholder="مثال: طريق الكينج - بجوار مسجد الفتح، كينج مريوط، العامرية"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-medium text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
            </div>

            <!-- Google Maps URL -->
            <div class="md:col-span-2 space-y-1.5">
                <label for="google_maps_url" class="block text-xs font-bold text-gray-900">
                    {{ __('رابط خرائط Google Maps') }}
                </label>
                <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url') }}" dir="ltr"
                       placeholder="https://maps.google.com/?q=..."
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-medium text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all text-right">
            </div>

            <!-- Working Hours (Box) -->
            <div class="md:col-span-2 p-5 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-3">
                <h4 class="text-xs font-black text-amber-950 flex items-center gap-2">
                    <span>⏰</span>
                    <span>{{ __('مواعيد وساعات العمل بالفرع') }}</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-amber-900 mb-1">{{ __('من الساعة') }}</label>
                        <input type="text" name="working_hours[from]" value="{{ old('working_hours.from', '08:00 ص') }}"
                               placeholder="08:00 ص"
                               class="w-full bg-white border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-amber-900 mb-1">{{ __('إلى الساعة') }}</label>
                        <input type="text" name="working_hours[to]" value="{{ old('working_hours.to', '02:00 ص') }}"
                               placeholder="02:00 ص"
                               class="w-full bg-white border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-amber-900 mb-1">{{ __('أيام العمل') }}</label>
                        <input type="text" name="working_hours[days]" value="{{ old('working_hours.days', 'يومياً طوال الأسبوع') }}"
                               placeholder="يومياً طوال الأسبوع"
                               class="w-full bg-white border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="space-y-1.5">
                <label for="image" class="block text-xs font-bold text-gray-900">
                    {{ __('صورة واجهة الفرع') }}
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-red-600 file:cursor-pointer">
            </div>

            <!-- Sort Order -->
            <div class="space-y-1.5">
                <label for="sort_order" class="block text-xs font-bold text-gray-900">
                    {{ __('ترتيب الظهور') }}
                </label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-full bg-canvas-base border border-orange-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-900 focus:border-red-500 focus:outline-none">
            </div>

            <!-- Is Active -->
            <div class="md:col-span-2 pt-2">
                <label class="flex items-center gap-3 cursor-pointer p-4 bg-canvas-base rounded-2xl border border-orange-100">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-orange-200">
                    <span class="text-xs font-bold text-gray-900">{{ __('الفرع نشط ومتاح للعملاء واستقبال الطلبات') }}</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.branches.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                {{ __('إلغاء') }}
            </a>
            <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                {{ __('حفظ وتفعيل الفرع') }}
            </button>
        </div>
    </form>

</div>
@endsection
