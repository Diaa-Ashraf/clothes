@extends('layouts.admin')

@section('title', __('تفاصيل طلب التوظيف'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
 
 <!-- Top Header -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <a href="{{ route('admin.job-applications.index') }}" class="text-xs font-bold text-[#DC2626] hover:underline">
 ← {{ __('العودة لقائمة طلبات التوظيف') }}
 </a>
 </div>
 <h1 class="font-sans text-xl sm:text-2xl font-bold text-[#1F2937]">
 {{ __('طلب توظيف: ') . $application->name }}
 </h1>
 <p class="text-xs text-gray-500 mt-1">
 {{ __('تم التقديم بتاريخ: ') }} {{ $application->created_at->format('Y-m-d H:i') }}
 </p>
 </div>

 <div>
 @if($application->status === 'pending')
 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
 قيد المراجعة
 </span>
 @elseif($application->status === 'reviewed')
 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
 تمت المراجعة
 </span>
 @elseif($application->status === 'accepted')
 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
 مقبول
 </span>
 @elseif($application->status === 'rejected')
 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
 مرفوض
 </span>
 @endif
 </div>
 </div>

 <!-- Application Details Grid -->
 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
 
 <!-- Left 2 Cols: Details -->
 <div class="md:col-span-2 space-y-6">
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm space-y-4">
 <h3 class="font-bold text-sm text-[#1F2937] border-b border-[border-orange-100] pb-3">
 بيانات المتقدم والوظيفة
 </h3>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
 <div>
 <span class="block text-gray-500 mb-1">الاسم الكامل</span>
 <strong class="text-sm text-[#1F2937]">{{ $application->name }}</strong>
 </div>

 <div>
 <span class="block text-gray-500 mb-1">الوظيفة المطلوبة</span>
 <strong class="text-sm text-[#1F2937]">{{ $application->position ?: 'غير محدد' }}</strong>
 </div>

 <div>
 <span class="block text-gray-500 mb-1">رقم الهاتف</span>
 <a href="tel:{{ $application->phone }}" class="text-sm font-bold text-primary-700 hover:underline" dir="ltr">
 {{ $application->phone }}
 </a>
 </div>

 <div>
 <span class="block text-gray-500 mb-1">البريد الإلكتروني</span>
 <span class="text-sm text-[#1F2937]">{{ $application->email ?: 'غير متوفر' }}</span>
 </div>

 <div class="sm:col-span-2">
 <span class="block text-gray-500 mb-1">الفرع المفضل للعمل</span>
 <strong class="text-sm text-[#1F2937]">{{ $application->branch?->name ?? 'أي فرع متاح بالعامرية' }}</strong>
 </div>
 </div>

 @if($application->message)
 <div class="pt-3 border-t border-[border-orange-100]">
 <span class="block text-gray-500 text-xs mb-1.5">رسالة المتقدم / نبذة عن الخبرات:</span>
 <div class="p-3.5 rounded-xl bg-[bg-canvas-base] text-xs text-[#1F2937] leading-relaxed">
 {{ $application->message }}
 </div>
 </div>
 @endif
 </div>

 <!-- CV Attachment -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm space-y-3">
 <h3 class="font-bold text-sm text-[#1F2937] border-b border-[border-orange-100] pb-3">
 السيرة الذاتية (CV)
 </h3>

 @if($application->cv_file)
 <div class="flex items-center justify-between p-4 rounded-xl bg-[bg-canvas-base] border border-[border-orange-100]">
 <div class="flex items-center gap-3">
 <span class="text-3xl"></span>
 <div>
 <p class="text-xs font-bold text-[#1F2937]">ملف السيرة الذاتية المرفق</p>
 <span class="text-[10px] text-gray-500">{{ pathinfo($application->cv_file, PATHINFO_BASENAME) }}</span>
 </div>
 </div>

 <a href="{{ route('admin.job-applications.download', $application->id) }}" 
 class="px-4 py-2 rounded-xl bg-[#1F2937] hover:bg-[#DC2626] text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2">
 <span>⬇</span>
 <span>تحميل الملف</span>
 </a>
 </div>
 @else
 <p class="text-xs text-gray-500 py-2">لم يقم المتقدم برفع ملف سيرة ذاتية.</p>
 @endif
 </div>
 </div>

 <!-- Right Col: Status Management -->
 <div class="space-y-6">
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm space-y-4">
 <h3 class="font-bold text-sm text-[#1F2937] border-b border-[border-orange-100] pb-3">
 تعديل حالة الطلب
 </h3>

 <form action="{{ route('admin.job-applications.update-status', $application->id) }}" method="POST" class="space-y-3">
 @csrf
 @method('PUT')

 <div>
 <label for="status" class="block text-xs font-bold text-[#1F2937] mb-1.5">اختر الحالة الجديدة:</label>
 <select name="status" id="status" class="w-full bg-[bg-canvas-base] border border-[border-orange-100] rounded-xl px-3.5 py-2.5 text-xs text-[#1F2937] focus:outline-none">
 <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
 <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
 <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>مقبول</option>
 <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>مرفوض</option>
 </select>
 </div>

 <button type="submit" class="w-full py-2.5 rounded-xl bg-[#1F2937] hover:bg-[#DC2626] text-white text-xs font-bold transition-all shadow-sm">
 تحديث الحالة
 </button>
 </form>

 <!-- Quick WhatsApp Message to Applicant -->
 <div class="pt-4 border-t border-[border-orange-100]">
 <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}?text={{ urlencode('مرحباً ' . $application->name . '، بخصوص طلب توظيفك في ألبان التوحيد...') }}" 
 target="_blank" 
 rel="noopener noreferrer" 
 class="w-full py-2.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition-colors flex items-center justify-center gap-2">
 <span></span>
 <span>مراسلة المتقدم عبر واتساب</span>
 </a>
 </div>
 </div>

 <!-- Danger Zone -->
 <div class="bg-white border border-red-200 rounded-2xl p-6 shadow-sm space-y-3">
 <h4 class="font-bold text-xs text-red-700">حذف الطلب نهائياً</h4>
 <p class="text-[11px] text-gray-500">سيتم مسح بيانات المتقدم وملف السيرة الذاتية بشكل كامل.</p>
 <form action="{{ route('admin.job-applications.destroy', $application->id) }}" method="POST" data-confirm-message="هل أنت متأكد من حذف هذا الطلب؟">
 @csrf
 @method('DELETE')
 <button type="submit" class="w-full py-2 px-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors shadow-sm cursor-pointer">
 حذف طلب التوظيف
 </button>
 </form>
 </div>
 </div>

 </div>

</div>
@endsection
