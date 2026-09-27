@extends('layouts.admin')

@section('title', __('تعديل التصنيف'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-[#1F2937]">{{ __('تعديل التصنيف') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ __('تعديل تفاصيل الفئة') }}: <span class="font-semibold text-[#1F2937]">{{ $category->name }}</span></p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[#EDE8DC] text-[#1F2937] text-sm font-semibold transition-colors">
            {{ __('العودة للتصنيفات') }}
        </a>
    </div>

    <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-semibold text-[#1F2937] mb-1.5">{{ __('اسم التصنيف') }} <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[border-orange-100] bg-white text-[#1F2937] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] focus:outline-none text-sm transition-all @error('name') border-rose-500 @enderror"
                           value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-[#1F2937] mb-1.5">{{ __('الرابط الدائم (Slug)') }}</label>
                    <input type="text" id="slug" name="slug" 
                           class="w-full px-4 py-2.5 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base]/40 text-[#1F2937] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] focus:outline-none text-sm transition-all @error('slug') border-rose-500 @enderror" 
                           value="{{ old('slug', $category->slug) }}">
                    @error('slug')
                        <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-[#1F2937] mb-1.5">{{ __('الوصف') }}</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[border-orange-100] bg-white text-[#1F2937] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] focus:outline-none text-sm transition-all @error('description') border-rose-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-semibold text-[#1F2937] mb-2">{{ __('صورة التصنيف') }}</label>
                @if($category->image_url)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $category->image_url }}" class="w-14 h-14 rounded-xl object-cover border border-[border-orange-100]" alt="{{ $category->name }}">
                        <span class="text-xs text-gray-500">الصورة الحالية</span>
                    </div>
                @endif
                <input type="file" id="image" name="image" 
                       class="w-full px-3.5 py-2 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base]/40 text-[#1F2937] text-sm focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#1F2937] file:text-white hover:file:bg-[#27272A] cursor-pointer @error('image') border-rose-500 @enderror">
                @error('image')
                    <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-[border-orange-100] flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[#EDE8DC] text-[#1F2937] text-sm font-semibold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-sm font-bold shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('حفظ التعديلات') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
