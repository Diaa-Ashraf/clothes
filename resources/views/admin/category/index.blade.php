@extends('layouts.admin')

@section('title', 'أقسام وتصنيفات الأزياء')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold text-clay uppercase tracking-wider">هيكل الأقسام والكتالوج</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                أقسام وتصنيفات الأزياء
            </h1>
            <p class="text-xs text-mocha mt-1">
                تنظيم وتصنيف تشكيلات الملابس في أقسام وأقسام فرعية تظهر في المتجر والقوائم الرئيسية.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>إضافة تصنيف جديد</span>
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs border-collapse">
                <thead class="bg-linen/60 text-mocha font-bold border-b border-sand/40 text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-start" style="width: 50px;">#</th>
                        <th class="py-3 px-4 text-start">الصورة</th>
                        <th class="py-3 px-4 text-start">اسم التصنيف والهيكل</th>
                        <th class="py-3 px-4 text-center">المنتجات المرتبطة</th>
                        <th class="py-3 px-4 text-center">الترتيب</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-4 text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/30">
                    @forelse($categories as $category)
                        <tr class="hover:bg-vanilla/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-taupe">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-10 h-10 rounded-xl bg-vanilla border border-sand/50 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($category->image_url)
                                        <img src="{{ $category->image_url }}" 
                                             alt="{{ $category->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-sm">🏷️</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-bold text-espresso text-xs">{{ $category->name }}</span>
                                    @if($category->parent)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-clay font-medium">
                                            <span class="text-taupe">تابع لـ:</span>
                                            <span>{{ $category->parent->name }}</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] text-taupe font-mono">قسم رئيسي</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-linen text-mocha border border-sand/40">
                                    {{ $category->products_count }} قطعة
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono text-taupe">
                                {{ $category->sort_order ?? 0 }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($category->is_active ?? true)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100/80 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-stone-100 text-stone-600 border border-stone-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                        موقوف
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="p-1.5 rounded-lg hover:bg-beige text-mocha hover:text-espresso transition-all" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-50 text-rose-500 hover:text-rose-700 transition-all cursor-pointer" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-taupe">
                                لا توجد تصنيفات مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'hasPages') && $categories->hasPages())
            <div class="p-4 border-t border-sand/40 bg-linen/20">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
