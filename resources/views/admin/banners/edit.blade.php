@extends('layouts.admin')

@section('title', 'تعديل البانر الترويجي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.banners.index') }}" class="hover:text-[#2B2118] transition-colors">بانرات الواجهة</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">تعديل البانر</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">تعديل شريحة البانر</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تحديث صورة البانر وموضع العرض ورابط التوجيه</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للبانرات</span>
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-red-50/50 border border-[#A9552F]/30 text-[#A9552F] text-xs space-y-1">
            <div class="font-bold">يرجى تصحيح الملاحظات التالية:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        موضع العرض في المتجر <span class="text-[#A9552F]">*</span>
                    </label>
                    <select name="position" required
                            class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none font-bold">
                        <option value="home_collection" {{ old('position', $banner->position) == 'home_collection' ? 'selected' : '' }}>
                            كولكشن ترويجي في منتصف الرئيسية (يظهر اثنان فقط جنباً إلى جنب)
                        </option>
                        <option value="main_slider" {{ old('position', $banner->position) == 'main_slider' ? 'selected' : '' }}>
                            شريحة بالسلايدر الرئيسي أعلى الصفحة
                        </option>
                    </select>
                    <span class="text-[11px] text-[#8F7B63] block">حدد "كولكشن ترويجي" ليظهر في الصندوقين الترويجيين بمنتصف الصفحة الرئيسية</span>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        صورة البانر أو الكولكشن
                    </label>
                    @if($banner->image_url)
                        <div class="p-2 bg-[#FBF6EA] border border-[#EADFC8] rounded-xl mb-2 flex items-center gap-3">
                            <img src="{{ $banner->image_url }}" alt="" class="w-16 h-16 object-cover rounded-xl border border-[#EADFC8]">
                            <span class="text-xs text-[#8F7B63]">الصورة الحالية المعروضة</span>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-xs text-[#6B5642] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-[#382416] file:text-[#FBF6EA] cursor-pointer">
                    <span class="text-[11px] text-[#8F7B63] block">(اتركها فارغة إذا كنت لا ترغب بتغيير الصورة الحالية)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        الوسم الترويجي / التاج العلوي (Badge)
                    </label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                           placeholder="مثال: كولكشن الشباب والرجال، أو كولكشن السيدات والفتيات"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        العنوان الرئيسي للبانر أو الكولكشن <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                           placeholder="مثال: بدل، بليزرات، وقمصان كلاسيك للعمل والمناسبات"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none font-bold">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#2B2118]">
                    الوصف الترويجي المختصر
                </label>
                <textarea name="description" rows="2"
                          placeholder="مثال: قصات سمارت كاجوال مريحة وتشطيب مصري فائق الدقة ومناسب لكافة الأوقات."
                          class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">{{ old('description', $banner->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        نص زر الإجراء (Button Text)
                    </label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?: 'تسوق الكولكشن الآن') }}"
                           placeholder="مثال: تسوق الرجالي الآن"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        رابط التوجيه (Target URL)
                    </label>
                    <input type="text" name="url" value="{{ old('url', $banner->url ?? $banner->link) }}" dir="ltr"
                           placeholder="/shop?category_id=9 أو https://..."
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] outline-none text-right">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        لون خلفية الكارت (Hex Color)
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="editColorPicker" value="{{ old('bg_color', $banner->bg_color ?: '#AC9653') }}"
                               oninput="document.getElementById('editBgColorInput').value = this.value"
                               class="w-10 h-10 rounded-lg border border-[#EADFC8] p-0.5 cursor-pointer bg-transparent shrink-0">
                        <input type="text" id="editBgColorInput" name="bg_color" value="{{ old('bg_color', $banner->bg_color ?: '#AC9653') }}"
                               oninput="document.getElementById('editColorPicker').value = this.value"
                               class="flex-1 px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs font-mono text-[#2B2118] focus:border-[#2B2118] outline-none">
                    </div>
                </div>
            </div>

            <!-- Active Status -->
            <div class="pt-2 border-t border-[#EADFC8]">
                <label class="relative inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-[#EADFC8] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#382416]"></div>
                    <span class="text-xs font-bold text-[#2B2118]">تفعيل البانر / الكولكشن وظهوره في واجهة المتجر</span>
                </label>
            </div>

            <div class="pt-4 border-t border-[#EADFC8] flex items-center justify-end gap-3">
                <a href="{{ route('admin.banners.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
