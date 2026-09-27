@extends('layouts.admin')

@section('title', __('سجل الإشعارات والتنبيهات'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-100">
                    🔔 مركز التنبيهات الفورية
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500 font-medium">سجل العمليات والطلبات</span>
            </div>
            <h1 class="font-sans text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('الإشعارات والتنبيهات') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                {{ __('متابعة فورية لكافة الطلبات الواردة، رسائل واستفسارات العملاء، وطلبات التوظيف.') }}
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" 
                    onclick="markAllNotificationsRead()" 
                    class="px-4 py-2.5 rounded-2xl bg-white hover:bg-gray-50 border border-gray-200 text-xs font-bold text-gray-700 transition-all shadow-2xs cursor-pointer flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('تحديد الكل كمقروء') }}</span>
            </button>

            <button type="button" 
                    onclick="clearAllNotifications()" 
                    class="px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-xs font-bold text-rose-700 transition-all shadow-2xs cursor-pointer flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>{{ __('مسح السجل') }}</span>
            </button>
        </div>
    </div>

    <!-- Notifications List Card -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xs overflow-hidden">
        
        @if($notifications->count() > 0)
        <div class="divide-y divide-gray-100" id="notifications-full-list">
            @foreach($notifications as $item)
            @php
                $typeBg = match($item->type) {
                    'order' => 'bg-red-50 text-red-600 border-red-100',
                    'message' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'job' => 'bg-purple-50 text-purple-600 border-purple-100',
                    'review' => 'bg-amber-50 text-amber-600 border-amber-100',
                    default => 'bg-gray-50 text-gray-600 border-gray-100',
                };
                $typeEmoji = match($item->type) {
                    'order' => '🛒',
                    'message' => '💬',
                    'job' => '💼',
                    'review' => '⭐',
                    default => '🔔',
                };
            @endphp
            <div class="p-4 sm:p-5 flex items-start justify-between gap-4 transition-colors {{ $item->is_read ? 'bg-white hover:bg-gray-50/50' : 'bg-red-50/30 hover:bg-red-50/50 border-s-4 border-red-500' }}" id="notif-row-{{ $item->id }}">
                <div class="flex items-start gap-3.5 min-w-0 flex-1">
                    <div class="w-10 h-10 rounded-2xl {{ $typeBg }} border flex items-center justify-center text-lg shrink-0 shadow-2xs">
                        {{ $typeEmoji }}
                    </div>

                    <div class="min-w-0 flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 truncate">
                                {{ $item->title }}
                            </h3>
                            @if(!$item->is_read)
                            <span class="inline-block w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $item->message }}
                        </p>
                        <div class="flex items-center gap-3 text-[11px] text-gray-400 font-medium pt-1">
                            <span>{{ $item->time_ago }}</span>
                            @if($item->link && $item->link !== '#')
                            <span>•</span>
                            <a href="{{ $item->link }}" onclick="markAsReadInline({{ $item->id }})" class="text-primary-800 font-bold hover:underline">
                                عرض التفاصيل ←
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @if(!$item->is_read)
                    <button type="button" 
                            onclick="markAsReadInline({{ $item->id }})" 
                            title="تحديد كمقروء"
                            class="p-2 rounded-xl bg-gray-50 hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 border border-gray-200 transition-colors cursor-pointer text-xs">
                        ✓
                    </button>
                    @endif

                    <button type="button" 
                            onclick="deleteNotificationItem({{ $item->id }})" 
                            title="حذف الإشعار"
                            class="p-2 rounded-xl bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-600 border border-gray-200 transition-colors cursor-pointer text-xs">
                        ✕
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $notifications->links() }}
        </div>
        @endif

        @else
        <div class="p-12 text-center space-y-3">
            <div class="w-16 h-16 rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-center text-3xl mx-auto text-gray-400">
                🔕
            </div>
            <h3 class="text-base font-bold text-gray-800">لا توجد إشعارات حالياً</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                ستظهر هنا كافة الإشعارات والتنبيهات عند قيام العملاء بطلب منتجات أو إرسال استفسارات أو تقديم طلبات توظيف.
            </p>
        </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
    function markAsReadInline(id) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const row = document.getElementById(`notif-row-${id}`);
            if (row) {
                row.classList.remove('bg-red-50/30', 'border-s-4', 'border-red-500');
                row.classList.add('bg-white');
            }
            if (window.refreshAdminNotifications) {
                window.refreshAdminNotifications();
            }
        });
    }

    function deleteNotificationItem(id) {
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const row = document.getElementById(`notif-row-${id}`);
            if (row) row.remove();
            showToast('تم حذف الإشعار', 'success');
            if (window.refreshAdminNotifications) {
                window.refreshAdminNotifications();
            }
        });
    }

    function markAllNotificationsRead() {
        fetch('/admin/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            showToast('تم تحديد جميع الإشعارات كمقروءة', 'success');
            setTimeout(() => window.location.reload(), 500);
        });
    }

    function clearAllNotifications() {
        if (!confirm('هل أنت متأكد من مسح جميع الإشعارات؟')) return;

        fetch('/admin/notifications/clear-all', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            showToast('تم مسح جميع الإشعارات', 'success');
            setTimeout(() => window.location.reload(), 500);
        });
    }
</script>
@endpush
@endsection
