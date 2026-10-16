@extends('layouts.admin')

@section('title', 'تعديل التصنيف: ' . $category->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-espresso">تعديل التصنيف: {{ $category->name }}</h1>
            <p class="text-xs text-mocha mt-1">تحديث بيانات وقسم وتفاصيل تصنيف الأزياء</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
            عودة للأقسام
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-espresso mb-1.5">
                        اسم التصنيف <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}" 
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
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                           value="{{ old('slug', $category->slug) }}" 
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
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}" 
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
                          class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('description') border-rose-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-bold text-espresso mb-1.5">
                    صورة الغلاف / التصنيف
                </label>
                @if($category->image)
                    <div class="mb-3 flex items-center gap-3 p-3 rounded-xl bg-vanilla/40 border border-sand/40 w-fit">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-12 h-12 rounded-lg object-cover border border-sand/50" alt="{{ $category->name }}">
                        <span class="text-xs text-mocha">الصورة الحالية محفوظة</span>
                    </div>
                @endif
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-vanilla/20 text-espresso text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action file:text-action-ink hover:file:bg-espresso cursor-pointer @error('image') border-rose-500 @enderror">
                <p class="text-[11px] text-taupe mt-1">اترك الحقل فارغاً إذا كنت لا ترغب بتغيير الصورة.</p>
                @error('image')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1" 
                       {{ old('is_active', $category->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
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
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
