@extends('layouts.admin')

@section('title', 'العلامات التجارية')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <h1 class="text-xl font-bold text-espresso">العلامات التجارية والمصممين</h1>
            </div>
            <p class="text-xs text-mocha mt-1">إدارة بيوت الأزياء والماركات العالمية والمحلية المسجلة في المتجر</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.brands.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة علامة تجارية
            </a>
        </div>
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

    {{-- Filter & Search Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث باسم العلامة..." 
                       class="w-full pl-3 pr-9 py-2 text-xs rounded-xl bg-vanilla/40 border border-sand/60 text-espresso placeholder-taupe focus:outline-none focus:border-action transition-all">
                <svg class="w-4 h-4 text-taupe absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                @if(request('search'))
                    <a href="{{ route('admin.brands.index') }}" class="px-3 py-2 text-xs text-mocha hover:text-espresso underline">إلغاء البحث</a>
                @endif
                <button type="submit" class="px-4 py-2 bg-linen hover:bg-beige text-espresso text-xs font-semibold rounded-xl border border-sand/60 transition-all">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-linen/60 border-b border-sand/40 text-[11px] font-bold text-mocha uppercase tracking-wider">
                        <th class="py-3 px-4">العلامة والشعار</th>
                        <th class="py-3 px-4">الرابط الدائم (Slug)</th>
                        <th class="py-3 px-4 text-center">المنتجات المرتبطة</th>
                        <th class="py-3 px-4 text-center">الترتيب</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/30 text-xs">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-vanilla/30 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-vanilla border border-sand/60 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                                        @else
                                            <span class="text-xs font-bold text-taupe">{{ mb_substr($brand->name, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-espresso">{{ $brand->name }}</div>
                                        @if($brand->website)
                                            <a href="{{ $brand->website }}" target="_blank" class="text-[10px] text-clay hover:underline flex items-center gap-1 mt-0.5">
                                                <span>{{ Str::limit($brand->website, 25) }}</span>
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-[11px] text-taupe dir-ltr text-right">
                                {{ $brand->slug }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-linen text-mocha border border-sand/40">
                                    {{ $brand->products_count }} قطعة
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center text-taupe font-mono">
                                {{ $brand->sort_order }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($brand->is_active)
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

                            <td class="py-3.5 px-4 text-left">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                       class="p-1.5 rounded-lg hover:bg-beige text-mocha hover:text-espresso transition-all" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه العلامة التجارية؟');" class="inline-block">
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
                            <td colspan="6" class="py-12 text-center text-taupe">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-espresso">لا توجد علامات تجارية مسجلة</p>
                                    <p class="text-xs text-mocha">ابدأ بإضافة العلامات التجارية ودور الأزياء لعرض منتجاتها في الكتالوج.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands->hasPages())
            <div class="p-4 border-t border-sand/40 bg-linen/20">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
