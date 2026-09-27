@extends('layouts.admin')

@section('title', __('إدارة الفروع'))

@section('content')
<div class="space-y-6">
 
 <!-- Top Header -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-[#DC2626] uppercase tracking-wider"> Branches Management</span>
 </div>
 <h1 class="font-sans text-2xl sm:text-3xl font-bold text-[#1F2937]">
 {{ __('فروع ألبان وسوبرماركت التوحيد') }}
 </h1>
 <p class="text-xs sm:text-sm text-gray-500 mt-1">
 {{ __('إدارة الفروع الأربعة بمنطقة العامرية، العناوين، أرقام التواصل، ومواعيد العمل.') }}
 </p>
 </div>

        <a href="{{ route('admin.branches.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-2 shrink-0 cursor-pointer">
            <span>+</span>
            <span>{{ __('إضافة فرع جديد') }}</span>
        </a>
    </div>

 <!-- Branches Table -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl shadow-sm overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full text-start text-xs">
 <thead class="bg-[bg-canvas-base] text-gray-500 uppercase text-[11px] border-b border-[border-orange-100]">
 <tr>
 <th class="py-3.5 px-6 text-start font-semibold" style="width: 60px;">#</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('الصورة') }}</th>
 <th class="py-3.5 px-6 text-start font-semibold">{{ __('اسم الفرع') }}</th>
 <th class="py-3.5 px-6 text-start font-semibold">{{ __('العنوان') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('التواصل') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('أوقات العمل') }}</th>
 <th class="py-3.5 px-4 text-center font-semibold">{{ __('الحالة') }}</th>
 <th class="py-3.5 px-6 text-end font-semibold">{{ __('إجراءات') }}</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-[border-orange-100]">
 @forelse($branches as $branch)
 <tr class="hover:bg-[bg-canvas-base]/50 transition-colors">
 <td class="py-4 px-6 font-bold text-gray-500">
 {{ $loop->iteration }}
 </td>
 <td class="py-4 px-4">
 <div class="w-12 h-12 rounded-xl bg-[bg-canvas-base] border border-[border-orange-100] p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
 @if($branch->image_url)
 <img src="{{ $branch->image_url }}" 
 alt="{{ $branch->name }}"
 class="w-full h-full object-cover rounded-lg">
 @else
 <span class="text-xl"></span>
 @endif
 </div>
 </td>
 <td class="py-4 px-6">
 <span class="font-bold text-[#1F2937] text-xs block">{{ $branch->name }}</span>
 <span class="text-[10px] text-gray-500">{{ $branch->slug }}</span>
 </td>
 <td class="py-4 px-6 text-gray-500 max-w-xs">
 <span>{{ $branch->address }}</span>
 @if($branch->google_maps_url)
 <a href="{{ $branch->google_maps_url }}" target="_blank" class="block text-[10px] text-[#DC2626] hover:underline mt-0.5">
 فتح في خرائط Google
 </a>
 @endif
 </td>
 <td class="py-4 px-4 text-gray-500 space-y-1">
 @if($branch->phone)
 <div class="flex items-center gap-1 text-[11px]" dir="ltr">
 <span></span>
 <span>{{ $branch->phone }}</span>
 </div>
 @endif
 @if($branch->whatsapp)
 <div class="flex items-center gap-1 text-[11px] text-emerald-600" dir="ltr">
 <span></span>
 <span>{{ $branch->whatsapp }}</span>
 </div>
 @endif
 </td>
 <td class="py-4 px-4 text-gray-500 text-[11px]">
 @if(is_array($branch->working_hours))
 <div>{{ $branch->working_hours['from'] ?? '' }} - {{ $branch->working_hours['to'] ?? '' }}</div>
 <div class="text-[10px] text-gray-500/70">{{ $branch->working_hours['days'] ?? '' }}</div>
 @else
 <span>يومياً</span>
 @endif
 </td>
 <td class="py-4 px-4 text-center">
 @if($branch->is_active)
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
 نشط
 </span>
 @else
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
 معطل
 </span>
 @endif
 </td>
 <td class="py-4 px-6 text-end">
 <div class="flex items-center justify-end gap-2">
 <a href="{{ route('admin.branches.edit', $branch->id) }}" class="px-3 py-1.5 rounded-lg border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[border-orange-100] text-[#1F2937] font-semibold text-xs transition-colors">
 {{ __('تعديل') }}
 </a>
 <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" data-confirm-message="{{ __('هل أنت متأكد من رغبتك في حذف فرع: ') . $branch->name . '؟' }}">
 @csrf
 @method('DELETE')
 <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 font-semibold text-xs transition-colors cursor-pointer">
 {{ __('حذف') }}
 </button>
 </form>
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="8" class="text-center py-12 text-gray-500">
 <span class="text-3xl block mb-2"></span>
 <p class="font-bold text-sm">{{ __('لا توجد فروع مسجلة حتى الآن.') }}</p>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if($branches->hasPages())
 <div class="p-4 border-t border-[border-orange-100]">
 {{ $branches->links() }}
 </div>
 @endif
 </div>

</div>
@endsection
