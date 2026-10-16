@extends('layouts.admin')

@section('title', 'تعديل الفرع: ' . $branch->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.branches.index') }}" class="hover:text-[#2B2118] transition-colors">الفروع وصالات العرض</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">تعديل: {{ $branch->name }}</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">تعديل بيانات الفرع / صالة العرض</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تحديث العنوان ومواعيد الاستقبال وأرقام التواصل للبوتيك</p>
        </div>
        <a href="{{ route('admin.branches.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للفروع</span>
        </a>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-xl bg-red-50/50 border border-[#A9552F]/30 text-[#A9552F] text-xs space-y-1">
                <p class="font-bold">يرجى تصحيح الملاحظات التالية:</p>
                <ul class="list-disc list-inside font-medium space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Branch Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-[#2B2118]">
                    اسم الفرع / البوتيك <span class="text-[#A9552F]">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $branch->name) }}" required
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all">
            </div>

            <!-- Slug -->
            <div class="space-y-1.5">
                <label for="slug" class="block text-xs font-bold text-[#2B2118]">
                    الرابط الدائم (Slug)
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $branch->slug) }}" dir="ltr"
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all text-right">
            </div>

            <!-- Phone -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-[#2B2118]">
                    رقم الهاتف للفرع
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $branch->phone) }}" dir="ltr"
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all text-right">
            </div>

            <!-- WhatsApp -->
            <div class="space-y-1.5">
                <label for="whatsapp" class="block text-xs font-bold text-[#2B2118]">
                    رقم خدمة عملاء الواتساب
                </label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $branch->whatsapp) }}" dir="ltr"
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all text-right">
            </div>

            <!-- Address (Full Width) -->
            <div class="md:col-span-2 space-y-1.5">
                <label for="address" class="block text-xs font-bold text-[#2B2118]">
                    العنوان التفصيلي وموقع الصالة <span class="text-[#A9552F]">*</span>
                </label>
                <input type="text" name="address" id="address" value="{{ old('address', $branch->address) }}" required
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all">
            </div>

            <!-- Google Maps URL -->
            <div class="md:col-span-2 space-y-1.5">
                <label for="google_maps_url" class="block text-xs font-bold text-[#2B2118]">
                    رابط الموقع على خرائط Google Maps
                </label>
                <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $branch->google_maps_url) }}" dir="ltr"
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] outline-none transition-all text-right">
            </div>

            <!-- Working Hours (Box) -->
            @php
                $hours = is_array($branch->working_hours) ? $branch->working_hours : [];
            @endphp
            <div class="md:col-span-2 p-5 rounded-2xl bg-[#FBF6EA] border border-[#EADFC8] space-y-3">
                <h4 class="text-xs font-serif font-bold text-[#2B2118] flex items-center gap-2">
                    <span>⏰</span>
                    <span>مواعيد وساعات العمل واستقبال الزوار</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-[#6B5642]">من الساعة</label>
                        <input type="text" name="working_hours[from]" value="{{ old('working_hours.from', $hours['from'] ?? '10:00 ص') }}"
                               class="w-full bg-white border border-[#EADFC8] rounded-xl px-3 py-2 text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-[#6B5642]">إلى الساعة</label>
                        <input type="text" name="working_hours[to]" value="{{ old('working_hours.to', $hours['to'] ?? '11:00 م') }}"
                               class="w-full bg-white border border-[#EADFC8] rounded-xl px-3 py-2 text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-[#6B5642]">أيام العمل</label>
                        <input type="text" name="working_hours[days]" value="{{ old('working_hours.days', $hours['days'] ?? 'يومياً طوال الأسبوع') }}"
                               class="w-full bg-white border border-[#EADFC8] rounded-xl px-3 py-2 text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="space-y-2">
                <label for="image" class="block text-xs font-bold text-[#2B2118]">
                    صورة واجهة البوتيك / صالة العرض
                </label>
                @if($branch->image_url)
                    <div class="flex items-center gap-3 p-2 bg-[#FBF6EA] rounded-xl border border-[#EADFC8] mb-2">
                        <img src="{{ $branch->image_url }}" alt="{{ $branch->name }}" class="w-12 h-12 object-cover rounded-lg border border-[#EADFC8]">
                        <span class="text-xs text-[#8F7B63]">الصورة الحالية للفرع</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full text-xs text-[#6B5642] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-[#382416] file:text-[#FBF6EA] cursor-pointer">
            </div>

            <!-- Sort Order -->
            <div class="space-y-1.5">
                <label for="sort_order" class="block text-xs font-bold text-[#2B2118]">
                    ترتيب الظهور
                </label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $branch->sort_order) }}" min="0"
                       class="w-full bg-white border border-[#EADFC8] rounded-xl px-4 py-2.5 text-xs text-[#2B2118] focus:border-[#2B2118] outline-none dir-ltr text-right">
            </div>
        </div>

        <!-- Active Toggle -->
        <div class="pt-2 border-t border-[#EADFC8]">
            <label class="relative inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-[#EADFC8] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#382416]"></div>
                <span class="text-xs font-bold text-[#2B2118]">تفعيل الفرع واستقبال الزوار والطلبات</span>
            </label>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EADFC8]">
            <a href="{{ route('admin.branches.index') }}" 
               class="px-5 py-2.5 rounded-xl border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium">
                إلغاء
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                تحديث بيانات الفرع
            </button>
        </div>
    </form>
</div>
@endsection
