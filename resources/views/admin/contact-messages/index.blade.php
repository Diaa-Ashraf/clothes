@extends('layouts.admin')

@section('title', __('رسائل واستفسارات العملاء'))

@section('content')
<div class="space-y-6">
 
 <!-- Top Header -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-[#DC2626] uppercase tracking-wider"> Customer Inquiries</span>
 </div>
 <h1 class="font-sans text-2xl sm:text-3xl font-bold text-[#1F2937]">
 {{ __('رسائل واستفسارات العملاء') }}
 </h1>
 <p class="text-xs sm:text-sm text-gray-500 mt-1">
 {{ __('متابعة الرسائل، الشكاوى، والاقتراحات الواردة من نموذج اتصل بنا بالموقع.') }}
 </p>
 </div>

 @if($unreadCount > 0)
 <div class="px-4 py-2.5 rounded-xl bg-primary-50 border border-primary-200 text-primary-800 text-xs font-bold flex items-center gap-2">
 <span class="w-2.5 h-2.5 rounded-full bg-primary-700 animate-ping"></span>
 <span>يوجد {{ $unreadCount }} رسائل جديدة غير مقروءة</span>
 </div>
 @endif
 </div>

 <!-- Filter Bar -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-4 shadow-sm flex items-center gap-2">
 <a href="{{ route('admin.contact-messages.index') }}" 
 class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ !request('filter') ? 'bg-[#1F2937] text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 جميع الرسائل
 </a>
 <a href="{{ route('admin.contact-messages.index', ['filter' => 'unread']) }}" 
 class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ request('filter') === 'unread' ? 'bg-primary-800 text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 <span>غير المقروءة</span>
 @if($unreadCount > 0)
 <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('filter') === 'unread' ? 'bg-white text-primary-800' : 'bg-primary-700 text-white' }}">
 {{ $unreadCount }}
 </span>
 @endif
 </a>
 <a href="{{ route('admin.contact-messages.index', ['filter' => 'read']) }}" 
 class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('filter') === 'read' ? 'bg-[#1F2937] text-white shadow-sm' : 'bg-[bg-canvas-base] text-gray-500 hover:bg-[border-orange-100]' }}">
 المقروءة
 </a>
 </div>

 <!-- Messages Table -->
 <div class="bg-white border border-[border-orange-100] rounded-2xl shadow-sm overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full text-start text-xs">
 <thead class="bg-[bg-canvas-base] text-gray-500 uppercase text-[11px] border-b border-[border-orange-100]">
 <tr>
 <th class="py-3.5 px-6 text-start font-semibold" style="width: 40px;"></th>
 <th class="py-3.5 px-6 text-start font-semibold">{{ __('المرسل') }}</th>
 <th class="py-3.5 px-6 text-start font-semibold">{{ __('الموضوع والرسالة') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('الهاتف / الإيميل') }}</th>
 <th class="py-3.5 px-4 text-start font-semibold">{{ __('التاريخ') }}</th>
 <th class="py-3.5 px-6 text-end font-semibold">{{ __('إجراءات') }}</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-[border-orange-100]">
 @forelse($messages as $msg)
 <tr class="hover:bg-[bg-canvas-base]/50 transition-colors {{ !$msg->is_read ? 'bg-primary-50/20 font-medium' : '' }}">
 <td class="py-4 px-6 text-center">
 @if(!$msg->is_read)
 <span class="w-2.5 h-2.5 rounded-full bg-primary-700 inline-block" title="غير مقروءة"></span>
 @else
 <span class="w-2.5 h-2.5 rounded-full bg-gray-300 inline-block" title="مقروءة"></span>
 @endif
 </td>
 <td class="py-4 px-6">
 <span class="text-xs block {{ !$msg->is_read ? 'font-bold text-[#1F2937]' : 'text-charcoal' }}">
 {{ $msg->name }}
 </span>
 </td>
 <td class="py-4 px-6 max-w-md">
 <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="hover:text-primary-700 transition-colors block">
 <span class="font-bold block {{ !$msg->is_read ? 'text-primary-900' : 'text-[#1F2937]' }}">
 {{ $msg->subject ?: 'بدون موضوع' }}
 </span>
 <p class="text-[11px] text-gray-500 truncate mt-0.5">
 {{ Str::limit($msg->message, 80) }}
 </p>
 </a>
 </td>
 <td class="py-4 px-4 text-gray-500 text-[11px]" dir="ltr">
 <div> {{ $msg->phone ?: '-' }}</div>
 @if($msg->email)
 <div> {{ $msg->email }}</div>
 @endif
 </td>
 <td class="py-4 px-4 text-gray-500 text-[11px]">
 {{ $msg->created_at->format('Y-m-d H:i') }}
 </td>
 <td class="py-4 px-6 text-end">
 <div class="flex items-center justify-end gap-2">
 <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="px-3 py-1.5 rounded-lg border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[border-orange-100] text-[#1F2937] font-semibold text-xs transition-colors">
 {{ __('قراءة') }}
 </a>
 <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" data-confirm-message="هل أنت متأكد من حذف هذه الرسالة؟">
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
 <td colspan="6" class="text-center py-12 text-gray-500">
 <span class="text-3xl block mb-2"></span>
 <p class="font-bold text-sm">{{ __('لا توجد رسائل مسجلة.') }}</p>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if($messages->hasPages())
 <div class="p-4 border-t border-[border-orange-100]">
 {{ $messages->links() }}
 </div>
 @endif
 </div>

</div>
@endsection
