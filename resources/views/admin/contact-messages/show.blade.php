@extends('layouts.admin')

@section('title', __('قراءة رسالة التواصل'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
 
 <!-- Top Header -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <a href="{{ route('admin.contact-messages.index') }}" class="text-xs font-bold text-[#DC2626] hover:underline">
 ← {{ __('العودة لقائمة الرسائل') }}
 </a>
 </div>
 <h1 class="font-sans text-xl sm:text-2xl font-bold text-[#1F2937]">
 {{ $message->subject ?: __('رسالة استفسار بدون عنوان') }}
 </h1>
 <p class="text-xs text-gray-500 mt-1">
 {{ __('تاريخ الإرسال: ') }} {{ $message->created_at->format('Y-m-d H:i') }}
 </p>
 </div>

 <div>
 <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
 تمت القراءة
 </span>
 </div>
 </div>

 <!-- Message Content Box -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
 <!-- Sender Details Strip -->
 <div class="p-4 rounded-xl bg-[bg-canvas-base] border border-[border-orange-100] grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
 <div>
 <span class="block text-gray-500 text-[11px] mb-1">اسم العميل</span>
 <strong class="text-sm text-[#1F2937]">{{ $message->name }}</strong>
 </div>

 <div>
 <span class="block text-gray-500 text-[11px] mb-1">رقم الهاتف</span>
 <span class="text-sm font-bold text-primary-800" dir="ltr">{{ $message->phone ?: 'غير محدد' }}</span>
 </div>

 <div>
 <span class="block text-gray-500 text-[11px] mb-1">البريد الإلكتروني</span>
 <span class="text-sm text-[#1F2937]">{{ $message->email ?: 'غير محدد' }}</span>
 </div>
 </div>

 <!-- Message Body -->
 <div class="space-y-2">
 <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">نص الرسالة:</h3>
 <div class="p-5 rounded-xl bg-canvas-base border border-orange-100 text-sm text-[#1F2937] leading-relaxed whitespace-pre-line">
 {{ $message->message }}
 </div>
 </div>

 <!-- Reply CTAs & Actions -->
 <div class="pt-4 border-t border-[border-orange-100] flex flex-wrap items-center justify-between gap-3">
 <div class="flex items-center gap-3">
 @if($message->phone)
 <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}?text={{ urlencode('مرحباً أستاذ ' . $message->name . '، رداً على رسالتكم لـ ' . settings('site_name', 'أزياء لافيستا') . '...') }}" 
 target="_blank" 
 rel="noopener noreferrer" 
 class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors flex items-center gap-2 shadow-sm">
 <span></span>
 <span>رد فوري عبر واتساب</span>
 </a>

 <a href="tel:{{ $message->phone }}" 
 class="px-4 py-2.5 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[border-orange-100] text-[#1F2937] font-bold text-xs transition-colors flex items-center gap-2" dir="ltr">
 <span></span>
 <span>اتصال بالعميل</span>
 </a>
 @endif

 @if($message->email)
 <a href="mailto:{{ $message->email }}?subject={{ urlencode('رد على استفسارك: ' . $message->subject) }}" 
 class="px-4 py-2.5 rounded-xl border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[border-orange-100] text-[#1F2937] font-bold text-xs transition-colors flex items-center gap-2">
 <span></span>
 <span>إرسال إيميل</span>
 </a>
 @endif
 </div>

 <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" data-confirm-message="هل أنت متأكد من حذف هذه الرسالة؟">
 @csrf
 @method('DELETE')
 <button type="submit" class="px-4 py-2.5 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs transition-colors cursor-pointer">
 حذف الرسالة
 </button>
 </form>
 </div>
 </div>

</div>
@endsection

