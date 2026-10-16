@extends('layouts.admin')

@section('title', 'إضافة تصنيف أزياء جديد')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-espresso">إضافة تصنيف أزياء جديد</h1>
            <p class="text-xs text-mocha mt-1">تحديد فئة جديدة أو قسم فرعي في كتالوج الملابس</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
            عودة للأقسام
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-espresso mb-1.5">
                        اسم التصنيف <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="مثال: فساتين سهرة، بدلات رسمية، عباءات" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('name') border-rose-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="parent_id" class="block text-xs font-bold text-espresso mb-1.5">
                        القسم الرئيسي (التسلسل الهرمي)
                    </label>
                    <select id="parent_id" 
                            name="parent_id" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('parent_id') border-rose-500 @enderror">
                        <option value="">-- قسم رئيسي مستقل (Root Level) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="slug" class="block text-xs font-bold text-espresso mb-1.5">
                        الرابط الدائم (Slug)
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}" 
                           placeholder="evening-dresses (يولد تلقائياً)" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('slug') border-rose-500 @enderror">
                    @error('slug')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-espresso mb-1.5">
                        ترتيب العرض
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
                <label for="description" class="block text-xs font-bold text-espresso mb-1.5">
                    وصف القسم
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          placeholder="وصف مختصر للقسم يظهر في ترويسة التصنيف وصفحات المتجر..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('description') border-rose-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-bold text-espresso mb-1.5">
                    صورة الغلاف / التصنيف
                </label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-vanilla/20 text-espresso text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action file:text-action-ink hover:file:bg-espresso cursor-pointer @error('image') border-rose-500 @enderror">
                <p class="text-[11px] text-taupe mt-1">يُفضل رفع صورة عالية الجودة بنسبة عرضية مناسبة (WebP / JPG / PNG).</p>
                @error('image')
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
                    تفعيل القسم وعرضه في شريط التصفح وقوائم المتجر
                </label>
            </div>

            <div class="pt-5 border-t border-sand/40 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-5 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold shadow-sm transition-all cursor-pointer">
                    حفظ التصنيف
                </button>
            </div>
        </form>
    </div>
</div>
@endsection