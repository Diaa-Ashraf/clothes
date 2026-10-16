@extends('layouts.admin')

@section('title', 'بانرات الواجهة والعروض الترويجية')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">بانرات الواجهة</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">بانرات الواجهة الرئيسية والإعلانات</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">إدارة شرائح البانر الرئيسي المتحرك وحملات التخفيضات المصورة</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>إضافة بانر جديد</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Info Banner for Collection feature -->
    <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] flex items-center justify-between gap-4 text-xs">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-[#AC9653]/15 text-[#AC9653] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="font-bold text-[#2B2118] block">كولكشنات منتصف الصفحة الرئيسية:</span>
                <span class="text-[#6B5642]">يتم تلقائياً عرض أول اثنين من الكولكشنات النشطة التي تم تحديد موضعها كـ <strong>«كولكشن ترويجي»</strong> جنباً إلى جنب في منتصف الصفحة الرئيسية.</span>
            </div>
        </div>
        <a href="{{ route('admin.banners.create') }}?position=home_collection" 
           class="px-3.5 py-1.5 rounded-lg bg-[#AC9653] text-white font-bold hover:bg-[#968244] transition-colors shrink-0">
            + إضافة كولكشن جديد
        </a>
    </div>

    <!-- Banners Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-[#6B5642] font-bold">
                        <th class="py-3.5 px-5" style="width: 60px;">#</th>
                        <th class="py-3.5 px-4">معاينة الصورة</th>
                        <th class="py-3.5 px-5">عنوان الكولكشن / البانر</th>
                        <th class="py-3.5 px-4">موضع العرض</th>
                        <th class="py-3.5 px-4">رابط التوجيه والزر</th>
                        <th class="py-3.5 px-4 text-center">الحالة</th>
                        <th class="py-3.5 px-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-5 font-mono text-xs text-[#8F7B63]">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-20 h-16 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] p-0.5 shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $banner->image_url }}" alt="Banner" class="w-full h-full object-cover rounded-lg">
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($banner->subtitle)
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#AC9653]/15 text-[#AC9653] mb-1">
                                        {{ $banner->subtitle }}
                                    </span>
                                @endif
                                <span class="font-bold text-xs text-[#2B2118] block">{{ $banner->title ?? 'بدون عنوان نصي' }}</span>
                                @if($banner->description)
                                    <span class="text-[11px] text-[#8F7B63] block truncate max-w-sm">{{ $banner->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($banner->position === 'home_collection')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#AC9653] text-white text-[11px] font-bold shadow-2xs">
                                        كولكشن الواجهة (يظهر 2)
                                    </span>
                                @elseif($banner->position === 'main_slider')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#382416] text-[#FBF6EA] text-[11px] font-medium">
                                        السلايدر الرئيسي
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#F3E9D2] border border-[#EADFC8] text-[11px] font-medium text-[#2B2118]">
                                        {{ $banner->position }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#8F7B63] text-[11px] dir-ltr text-right max-w-xs">
                                @if($banner->url)
                                    <a href="{{ $banner->url }}" target="_blank" class="hover:text-[#A9552F] hover:underline block truncate font-mono text-[10px]">
                                        {{ $banner->url }}
                                    </a>
                                @else
                                    <span>—</span>
                                @endif
                                @if($banner->button_text)
                                    <span class="text-[10px] text-[#2B2118] font-bold dir-rtl block">زر: {{ $banner->button_text }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($banner->status)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F3E9D2] text-[#8F7B63] border border-[#EADFC8]">
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا البانر؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-[#A9552F] text-xs font-medium transition-colors cursor-pointer">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-sm text-[#8F7B63]">
                                لا توجد بانرات ترويجية مضافة حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="p-4 border-t border-[#EADFC8] bg-[#FBF6EA]/50">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
