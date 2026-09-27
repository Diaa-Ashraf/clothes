@extends('layouts.admin')

@section('title', __('إدارة مجلات العروض والتخفيضات'))

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-red-50 text-red-700 border border-red-200">
                    📰 نظام حملات ومجلات العروض الأسبوعية
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500 font-medium">إصدارات مخصصة بضغطة زر</span>
            </div>
            <h1 class="font-sans text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('مجلات العروض الأسبوعية') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                {{ __('أنشئ إصدارات المجلة بأسعار وتخفيضات مخصصة لكل منتج وربطها بالعداد التنازلي والبانر في شاشة واحدة.') }}
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('offers.index') }}" target="_blank" class="px-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold transition-all flex items-center gap-2">
                <span>👁️</span>
                <span>{{ __('معاينة المجلة الحية') }}</span>
            </a>

            <a href="{{ route('admin.magazines.create') }}" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-2 cursor-pointer hover:scale-[1.02]">
                <span class="text-base">+</span>
                <span>{{ __('إنشاء إصدار مجلة جديد') }}</span>
            </a>
        </div>
    </div>

    <!-- Magazine Editions Grid / Table -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xs font-black text-gray-900 flex items-center gap-2">
                <span>📋</span>
                <span>{{ __('جميع إصدارات المجلات المسجلة') }} ({{ $magazines->total() }})</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-6 text-start font-bold">#</th>
                        <th class="py-3.5 px-4 text-start font-bold">{{ __('إصدار المجلة') }}</th>
                        <th class="py-3.5 px-4 text-start font-bold">{{ __('عدد المنتجات') }}</th>
                        <th class="py-3.5 px-4 text-start font-bold">{{ __('فترة سريان العرض') }}</th>
                        <th class="py-3.5 px-4 text-start font-bold">{{ __('العداد التنازلي') }}</th>
                        <th class="py-3.5 px-4 text-start font-bold">{{ __('الحالة الحالية') }}</th>
                        <th class="py-3.5 px-6 text-end font-bold">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($magazines as $mag)
                    <tr class="hover:bg-gray-50/60 transition-colors {{ $mag->is_active ? 'bg-amber-50/30' : '' }}">
                        <td class="py-4 px-6 font-bold text-gray-400">
                            {{ $loop->iteration }}
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr {{ $mag->is_active ? 'from-red-600 to-orange-500 text-white' : 'from-gray-200 to-gray-300 text-gray-700' }} flex items-center justify-center font-black text-sm shadow-xs shrink-0">
                                    📰
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-gray-900 text-xs">{{ $mag->title }}</span>
                                        @if($mag->is_active)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                المجلة النشطة حالياً للمتجر ✓
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $mag->badge_text }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-orange-50 border border-orange-200 text-orange-900 font-bold text-xs">
                                <span>🏷️</span>
                                <span>{{ $mag->items_count }} منتج في المجلة</span>
                            </span>
                        </td>

                        <td class="py-4 px-4">
                            <div class="text-xs space-y-0.5">
                                <span class="font-bold text-gray-800 block">{{ $mag->formatted_period }}</span>
                                @if($mag->ends_at)
                                    @if($mag->ends_at->isPast())
                                        <span class="text-[10px] font-bold text-rose-600">انتهت فترة العرض</span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-600">متبقي {{ $mag->ends_at->diffForHumans(['parts' => 2, 'short' => true]) }}</span>
                                    @endif
                                @else
                                    <span class="text-[10px] text-gray-400 font-medium">سارية ومستمرة</span>
                                @endif
                            </div>
                        </td>

                        <td class="py-4 px-4">
                            @if($mag->show_countdown)
                                <span class="text-emerald-700 font-bold flex items-center gap-1">
                                    <span>⏱️</span>
                                    <span>مفعل</span>
                                </span>
                            @else
                                <span class="text-gray-400 font-medium">معطل</span>
                            @endif
                        </td>

                        <td class="py-4 px-4">
                            <form action="{{ route('admin.magazines.toggle-active', $mag->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-black transition-all cursor-pointer {{ $mag->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200 hover:bg-gray-200' }}">
                                    {{ $mag->is_active ? 'نشطة (انقر للإيقاف)' : 'تفعيل ونشر للمتجر' }}
                                </button>
                            </form>
                        </td>

                        <td class="py-4 px-6 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.magazines.edit', $mag->id) }}" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 transition-colors title='تعديل'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.magazines.destroy', $mag->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف إصدار المجلة هذا؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors cursor-pointer title='حذف'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center text-xl mb-3">
                                📰
                            </div>
                            <span class="font-bold block text-gray-600 text-sm">لا توجد إصدارات مجلات حتى الآن</span>
                            <p class="text-xs text-gray-400 mt-1">ابدأ بإنشاء أول مجلة عروض أسبوعية وأضف منتجاتك المخفضة بسهولة.</p>
                            <a href="{{ route('admin.magazines.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 mt-4 rounded-xl bg-primary-800 text-white text-xs font-bold">
                                + إنشاء أول مجلة الآن
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($magazines->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $magazines->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
