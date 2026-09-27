@extends('layouts.admin')

@section('title', __('إدارة طلبات التوظيف'))

@section('content')
<div class="space-y-6">
 
 <!-- Top Header -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-[#DC2626] uppercase tracking-wider"> Recruitment & Careers</span>
 </div>
 <h1 class="font-sans text-2xl sm:text-3xl font-bold text-[#1F2937]">
 {{ __('طلبات التوظيف والتقديم') }}
 </h1>
 <p class="text-xs sm:text-sm text-gray-500 mt-1">
 {{ __('استعراض ومراجعة طلبات التوظيف المقدمة للعمل في فروع ألبان وسوبرماركت التوحيد.') }}
 </p>
 </div>
 </div>

 <!-- Filter Bar -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-4 shadow-sm">
 <form method="GET" action="{{ route('admin.job-applications.index') }}" class="flex flex-wrap items-center gap-3">
 <!-- Filter by Status -->
 <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
 <a href="{{ route('admin.job-applications.index', array_merge(request()->except('status', 'page'))) }}" 
 class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ !request('status') ? 'bg-[#1F2937] text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 الكل
 </a>
 <a href="{{ route('admin.job-applications.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
 class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 قيد المراجعة
 </a>
 <a href="{{ route('admin.job-applications.index', array_merge(request()->except('page'), ['status' => 'reviewed'])) }}" 
 class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status') === 'reviewed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 تمت المراجعة
 </a>
 <a href="{{ route('admin.job-applications.index', array_merge(request()->except('page'), ['status' => 'accepted'])) }}" 
 class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status') === 'accepted' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 مقبول
 </a>
 <a href="{{ route('admin.job-applications.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
 class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status') === 'rejected' ? 'bg-red-600 text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 مرفوض
 </a>
 </div>

 <!-- Filter by Branch -->
 <div class="ms-auto flex items-center gap-2">
 <select name="branch_id" onchange="this.form.submit()" class="bg-[bg-canvas-base] border border-[border-orange-100] rounded-xl px-3 py-1.5 text-xs text-[#1F2937] focus:outline-none">
 <option value="">{{ __('جميع الفروع') }}</option>
 @foreach($branches as $b)
 <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
 {{ $b->name }}
 </option>
 @endforeach
 </select>
 @if(request('status') || request('branch_id'))
 <a href="{{ route('admin.job-applications.index') }}" class="text-xs text-red-600 hover:underline">
 إلغاء الفلترة
 </a>
 @endif
 </div>
 </form>
 </div>

 <!-- Table -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl shadow-sm overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full text-start text-xs">
 <thead class="bg-[bg-canvas-base] text-gray-500 uppercase text-[11px] border-b border-[border-orange-100]">
 <tr>
 <th class="py-3.5 px-6 text-start font-semibold" style="width: 60px;">#</th>
 <th class="py-3.5 px-6 text-start font-semibold">{{ __('المتقدم') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('الوظيفة المطلوبة') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('الفرع المفضل') }}</th>
 <th class="py-3.5 px-4 text-center font-semibold">{{ __('السيرة الذاتية') }}</th>
 <th class="py-3.5 px-4 text-center font-semibold">{{ __('الحالة') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('تاريخ التقديم') }}</th>
 <th class="py-3.5 px-6 text-end font-semibold">{{ __('إجراءات') }}</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-[border-orange-100]">
 @forelse($applications as $app)
 <tr class="hover:bg-[bg-canvas-base]/50 transition-colors">
 <td class="py-4 px-6 font-bold text-gray-500">
 {{ $loop->iteration }}
 </td>
 <td class="py-4 px-6">
 <span class="font-bold text-[#1F2937] text-xs block">{{ $app->name }}</span>
 <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5" dir="ltr">
 <span> {{ $app->phone }}</span>
 @if($app->email)
 <span>•</span>
 <span> {{ $app->email }}</span>
 @endif
 </div>
 </td>
 <td class="py-4 px-4 font-semibold text-[#1F2937]">
 {{ $app->position ?: 'عام / غير محدد' }}
 </td>
 <td class="py-4 px-4 text-gray-500">
 {{ $app->branch?->name ?? 'أي فرع متاح' }}
 </td>
 <td class="py-4 px-4 text-center">
 @if($app->cv_file)
 <a href="{{ route('admin.job-applications.download', $app->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] border border-blue-200 transition-colors">
 <span></span>
 <span>تحميل CV</span>
 </a>
 @else
 <span class="text-[11px] text-gray-500">لا يوجد</span>
 @endif
 </td>
 <td class="py-4 px-4 text-center">
 @if($app->status === 'pending')
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
 قيد المراجعة
 </span>
 @elseif($app->status === 'reviewed')
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
 تمت المراجعة
 </span>
 @elseif($app->status === 'accepted')
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
 مقبول
 </span>
 @elseif($app->status === 'rejected')
 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
 مرفوض
 </span>
 @endif
 </td>
 <td class="py-4 px-4 text-gray-500 text-[11px]">
 {{ $app->created_at->format('Y-m-d H:i') }}
 </td>
 <td class="py-4 px-6 text-end">
 <div class="flex items-center justify-end gap-2">
 <a href="{{ route('admin.job-applications.show', $app->id) }}" class="px-3 py-1.5 rounded-lg border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[border-orange-100] text-[#1F2937] font-semibold text-xs transition-colors">
 {{ __('التفاصيل') }}
 </a>
 <form action="{{ route('admin.job-applications.destroy', $app->id) }}" method="POST" data-confirm-message="{{ __('هل أنت متأكد من حذف طلب التوظيف للمتقدم: ') . $app->name . '؟' }}">
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
 <p class="font-bold text-sm">{{ __('لا توجد طلبات توظيف تطابق البحث.') }}</p>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if($applications->hasPages())
 <div class="p-4 border-t border-[border-orange-100]">
 {{ $applications->links() }}
 </div>
 @endif
 </div>

</div>
@endsection
