@extends('layouts.admin')

@section('title', 'إضافة علامة تجارية')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-espresso">إضافة علامة تجارية جديدة</h1>
            <p class="text-xs text-mocha mt-1">سجل دار أزياء أو ماركة جديدة لربطها بمنتجات المتجر</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" 
           class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
            عودة للعلامات التجارية
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-espresso mb-1.5">
                        اسم العلامة التجارية <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="مثال: Atelier Riviera" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('name') border-rose-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-bold text-espresso mb-1.5">
                        الرابط الدائم (Slug)
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}"
                           placeholder="atelier-riviera (يولد تلقائياً إذا تُرك فارغاً)" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('slug') border-rose-500 @enderror">
                    @error('slug')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="website" class="block text-xs font-bold text-espresso mb-1.5">
                        الموقع الرسمي للعلامة
                    </label>
                    <input type="url" 
                           id="website" 
                           name="website" 
                           value="{{ old('website') }}"
                           placeholder="https://example.com" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('website') border-rose-500 @enderror">
                    @error('website')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-espresso mb-1.5">
                        ترتيب الظهور
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('sort_order') border-rose-500 @enderror">
                    @error('sort_order')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="logo" class="block text-xs font-bold text-espresso mb-1.5">
                    شعار العلامة التجارية (Logo)
                </label>
                <input type="file" 
                       id="logo" 
                       name="logo" 
                       accept="image/*"
                       class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-vanilla/20 text-espresso text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action file:text-action-ink hover:file:bg-espresso cursor-pointer @error('logo') border-rose-500 @enderror">
                <p class="text-[11px] text-taupe mt-1">يُفضل رفع شعار بخلفية شفافة بصيغة PNG أو WebP أو SVG (الحد الأقصى 2 ميجابايت).</p>
                @error('logo')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-espresso mb-1.5">
                    نبذة عن العلامة التجارية
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="تاريخ الدار، خطوط الإنتاج والأسلوب التصميمي..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('description') border-rose-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1" 
                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-action border-sand/80 focus:ring-action">
                <label for="is_active" class="text-xs font-bold text-espresso cursor-pointer">
                    تفعيل العلامة التجارية وإظهارها في المتجر والفلترة
                </label>
            </div>

            <div class="pt-5 border-t border-sand/40 flex items-center justify-end gap-3">
                <a href="{{ route('admin.brands.index') }}" 
                   class="px-5 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold shadow-sm transition-all cursor-pointer">
                    حفظ العلامة التجارية
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
