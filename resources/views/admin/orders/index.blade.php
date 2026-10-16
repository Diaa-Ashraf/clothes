@extends('layouts.admin')

@section('title', 'إدارة ومتابعة طلبات الأزياء')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="bg-cream border border-sand rounded-card p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-medium text-clay uppercase tracking-wider">مسار معالجة طلبات الأزياء</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-espresso">
                إدارة ومتابعة الطلبات
            </h1>
            <p class="text-xs sm:text-sm text-mocha mt-1">
                متابعة حركة طلبات العملاء، عناوين الشحن والتوصيل، مراجعة وتأكيد المدفوعات وتحديث مراحل التجهيز.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.reports.index') }}" class="btn-atelier-ghost text-xs">
                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>التقارير المالية</span>
            </a>
            <a href="{{ route('admin.orders.create') }}" class="btn-atelier-primary text-xs cursor-pointer">
                <span>+</span>
                <span>تسجيل طلب يدوي جديد</span>
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 rounded-xl bg-linen border border-sand text-status-success text-xs font-medium flex items-center gap-2 shadow-xs">
        <span class="text-base">✓</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (isset($errors) && $errors->any())
    <div class="p-4 rounded-xl bg-linen border border-sand text-status-danger text-xs font-medium space-y-1 shadow-xs">
        <span class="font-bold block text-sm">⚠️ تنبيه في تحديث الطلب:</span>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="bg-cream border border-sand rounded-card p-4 sm:p-6 shadow-xs">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="lg:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="بحث برقم الطلب، اسم العميل، الهاتف، أو إيصال التحويل..." 
                    class="w-full px-4 py-2 text-xs rounded-input bg-vanilla border border-sand text-espresso placeholder-taupe focus:outline-none focus:border-action focus:bg-cream">
            </div>

            <!-- Status Filter -->
            <div class="lg:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-input bg-vanilla border border-sand text-espresso focus:outline-none focus:border-action focus:bg-cream">
                    <option value="">-- جميع الحالات --</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>⏳ قيد المراجعة</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>📦 قيد الانتظار</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✓ تم التأكيد</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>⚙️ قيد التجهيز</option>
                    <option value="on_the_way" {{ request('status') == 'on_the_way' ? 'selected' : '' }}>🛵 في الطريق مع المندوب</option>
                    <option value="ready_for_pickup" {{ request('status') == 'ready_for_pickup' ? 'selected' : '' }}>🏬 جاهز للاستلام بالفرع</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>✅ تم التسليم بنجاح</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ ملغي</option>
                </select>
            </div>

            <!-- Delivery Type Filter -->
            <div class="lg:col-span-2">
                <select name="delivery_type" class="w-full px-3 py-2 text-xs rounded-input bg-vanilla border border-sand text-espresso focus:outline-none focus:border-action focus:bg-cream">
                    <option value="">-- نوع الاستلام --</option>
                    <option value="delivery" {{ request('delivery_type') == 'delivery' ? 'selected' : '' }}>🛵 توصيل للمنزل</option>
                    <option value="pickup" {{ request('delivery_type') == 'pickup' ? 'selected' : '' }}>🏪 استلام من الفرع</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div class="lg:col-span-2">
                <select name="payment_method" class="w-full px-3 py-2 text-xs rounded-input bg-vanilla border border-sand text-espresso focus:outline-none focus:border-action focus:bg-cream">
                    <option value="">-- طريقة الدفع --</option>
                    <option value="cash_on_delivery" {{ request('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>💵 الدفع عند الاستلام</option>
                    <option value="instapay" {{ request('payment_method') == 'instapay' ? 'selected' : '' }}>💳 تحويل إلكتروني / إنستاباي</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="lg:col-span-1 flex gap-1">
                <button type="submit" class="w-full py-2 px-3 rounded-input bg-action hover:bg-action-hover text-action-ink text-xs font-medium transition-all shadow-xs cursor-pointer" title="تطبيق">
                    تصفية
                </button>
                @if(request()->hasAny(['search', 'status', 'delivery_type', 'payment_method']))
                <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-input bg-linen border border-sand text-status-danger hover:bg-beige text-xs font-bold transition-colors shrink-0" title="إلغاء">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-cream border border-sand rounded-card shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-linen text-mocha font-medium border-b border-sand">
                    <tr>
                        <th class="py-3.5 px-6 text-start font-medium">رقم الطلب</th>
                        <th class="py-3.5 px-4 text-start font-medium">العميل والهاتف</th>
                        <th class="py-3.5 px-4 text-start font-medium">طريقة الاستلام</th>
                        <th class="py-3.5 px-4 text-start font-medium">الدفع والمبلغ</th>
                        <th class="py-3.5 px-4 text-start font-medium">الحالة الحالية</th>
                        <th class="py-3.5 px-4 text-start font-medium">تغيير الحالة (تحديث فوري)</th>
                        <th class="py-3.5 px-6 text-end font-medium">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/40">
                    @forelse($orders as $order)
                    <tr class="hover:bg-linen/40 transition-colors" id="order_row_{{ $order->id }}">
                        <!-- Order Number & Code -->
                        <td class="py-4 px-6 font-bold text-espresso">
                            <a href="{{ route('orders.show', $order->id) }}" class="hover:text-clay transition-colors block font-mono text-sm font-bold">
                                {{ $order->public_order_id ?? $order->order_number ?? '#' . $order->id }}
                            </a>
                            @if($order->orderItems->isNotEmpty())
                                <span class="text-[10px] text-taupe mt-0.5 block">
                                    {{ $order->orderItems->count() }} قطع
                                </span>
                            @endif
                        </td>

                        <!-- Customer Info -->
                        <td class="py-4 px-4">
                            <div class="font-bold text-espresso flex items-center gap-1.5 flex-wrap">
                                <span>👤</span>
                                <span>{{ $order->customer_display_name }}</span>
                            </div>
                            @if($order->phone)
                            <div class="mt-1 flex items-center gap-1.5">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}" target="_blank" class="text-xs font-bold text-status-success hover:underline font-mono inline-flex items-center gap-1" dir="ltr">
                                    <span>💬</span>
                                    <span>{{ $order->phone }}</span>
                                </a>
                            </div>
                            @endif
                            @if($order->address)
                            <div class="text-[11px] text-mocha mt-1 max-w-[200px] truncate" title="{{ $order->address }}">
                                📍 {{ $order->address }}
                            </div>
                            @endif
                        </td>

                        <!-- Delivery / Branch Pickup -->
                        <td class="py-4 px-4">
                            @if($order->delivery_type === 'pickup')
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-pill text-[10px] font-medium bg-linen text-mocha border border-sand">
                                        <span>🏪</span>
                                        <span>استلام من الفرع</span>
                                    </span>
                                    <div class="text-[11px] font-medium text-espresso">
                                        {{ $order->branch?->name ?? 'الفرع المحدد' }}
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-pill text-[10px] font-medium bg-linen text-mocha border border-sand">
                                    <span>🛵</span>
                                    <span>شحن وتوصيل</span>
                                </span>
                            @endif
                        </td>

                        <!-- Payment & Total -->
                        <td class="py-4 px-4">
                            <div class="font-bold text-sm text-espresso tabular-nums">
                                {{ format_currency($order->total_price ?? 0) }}
                            </div>
                            <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                @if($order->payment_method === 'instapay')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-linen text-clay border border-sand">
                                        <span>💳</span>
                                        <span>إنستاباي</span>
                                    </span>
                                    @if($order->payment_proof_image)
                                        <button type="button" 
                                            onclick="window.previewReceipt('{{ asset('storage/' . $order->payment_proof_image) }}', '{{ $order->public_order_id }}', '{{ $order->instapay_reference }}')"
                                            class="text-[10px] text-mocha bg-linen hover:bg-beige px-1.5 py-0.5 rounded border border-sand font-medium transition-colors cursor-pointer"
                                            title="معاينة إشعار التحويل">
                                            📄 الإشعار
                                        </button>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-linen text-mocha border border-sand">
                                        <span>💵</span>
                                        <span>كاش</span>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Current Status Badge (Warm Palette only) -->
                        <td class="py-4 px-4">
                            @php
                                $statusColors = [
                                    'under_review' => 'bg-linen text-status-warning border-sand font-medium',
                                    'pending' => 'bg-linen text-status-warning border-sand font-medium',
                                    'confirmed' => 'bg-beige text-espresso border-sand font-medium',
                                    'processing' => 'bg-beige text-espresso border-sand font-medium',
                                    'ready_for_pickup' => 'bg-linen text-mocha border-sand font-medium',
                                    'on_the_way' => 'bg-linen text-mocha border-sand font-medium',
                                    'shipped' => 'bg-linen text-mocha border-sand font-medium',
                                    'delivered' => 'bg-linen text-status-success border-sand font-bold',
                                    'cancelled' => 'bg-linen text-status-danger border-sand font-medium',
                                ];
                                $statusLabels = [
                                    'under_review' => '⏳ قيد المراجعة',
                                    'pending' => '📦 قيد الانتظار',
                                    'confirmed' => '✓ تم التأكيد',
                                    'processing' => '⚙️ قيد التجهيز',
                                    'ready_for_pickup' => '🏬 جاهز للاستلام',
                                    'on_the_way' => '🛵 في الطريق مع المندوب',
                                    'shipped' => '🚚 تم الشحن',
                                    'delivered' => '✅ تم التسليم بنجاح',
                                    'cancelled' => '❌ ملغي',
                                ];
                                $colorClass = $statusColors[$order->status] ?? 'bg-linen text-mocha border-sand';
                                $label = $statusLabels[$order->status] ?? $order->status;
                            @endphp
                            <span id="order_badge_{{ $order->id }}" class="px-2.5 py-1 rounded-pill border text-[11px] {{ $colorClass }} inline-block transition-all duration-300">
                                {{ $label }}
                            </span>
                        </td>

                        <!-- Change Status Select Dropdown (AJAX instant update) -->
                        <td class="py-4 px-4">
                            <div class="inline-flex items-center gap-2">
                                <select name="status" 
                                    id="order_select_{{ $order->id }}"
                                    data-prev-status="{{ $order->status }}"
                                    data-update-url="{{ route('admin.orders.update-status', $order->id) }}"
                                    onchange="window.handleStatusChange(this, '{{ $order->id }}', '{{ $order->public_order_id ?? $order->order_number ?? '#' . $order->id }}', '{{ addslashes($order->customer_display_name) }}')"
                                    class="text-[11px] py-1 ps-2 pe-6 rounded-lg bg-vanilla border border-sand text-espresso focus:outline-none focus:border-action cursor-pointer font-medium transition-all">
                                    <option value="under_review" {{ $order->status === 'under_review' ? 'selected' : '' }}>⏳ قيد المراجعة</option>
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>📦 قيد الانتظار</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>✓ تم التأكيد</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>⚙️ قيد التجهيز</option>
                                    @if($order->delivery_type === 'pickup')
                                        <option value="ready_for_pickup" {{ $order->status === 'ready_for_pickup' ? 'selected' : '' }}>🏬 جاهز للاستلام بالفرع</option>
                                    @else
                                        <option value="on_the_way" {{ $order->status === 'on_the_way' ? 'selected' : '' }}>🛵 في الطريق مع المندوب</option>
                                    @endif
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>✅ تم التسليم</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ إلغاء الطلب</option>
                                </select>
                                <span id="order_spinner_{{ $order->id }}" class="hidden text-xs animate-spin text-taupe">⌛</span>
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="py-4 px-6 text-end text-[11px] text-taupe font-mono">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-taupe">
                            لا توجد طلبات تطابق معايير البحث الحالية.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-sand bg-cream">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    <!-- InstaPay Receipt Preview Modal -->
    <div id="receiptModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-espresso/60 backdrop-blur-xs transition-opacity duration-200" dir="rtl">
        <div class="bg-cream rounded-2xl border border-sand max-w-lg w-full p-6 space-y-4 shadow-xl">
            <div class="flex items-center justify-between border-b border-sand pb-3">
                <h3 class="font-bold text-sm text-espresso" id="receiptModalTitle">إشعار التحويل الإلكتروني</h3>
                <button type="button" onclick="window.closeReceiptModal()" class="text-taupe hover:text-espresso text-lg">✕</button>
            </div>
            <div class="rounded-xl overflow-hidden bg-vanilla border border-sand flex items-center justify-center max-h-[70vh]">
                <img id="receiptModalImg" src="" alt="إشعار التحويل" class="max-w-full max-h-[65vh] object-contain">
            </div>
            <div id="receiptModalRef" class="text-xs text-mocha font-mono bg-linen p-2.5 rounded-lg border border-sand"></div>
            <div class="flex justify-end">
                <button type="button" onclick="window.closeReceiptModal()" class="btn-atelier-primary text-xs cursor-pointer">
                    إغلاق
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Order Confirmation Modal -->
    <div id="luxuryCancelOrderModal" 
         class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-espresso/60 backdrop-blur-xs transition-opacity duration-200 opacity-0 pointer-events-none"
         dir="rtl">
        
        <div id="luxuryCancelOrderCard" 
             class="bg-cream rounded-2xl border border-sand max-w-lg w-full p-6 sm:p-8 shadow-xl space-y-5 transform transition-all duration-200 scale-95">
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-linen border border-sand text-status-danger flex items-center justify-center text-2xl shrink-0">
                    ⚠️
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-espresso">إلغاء الطلب واسترجاع كميات المخزون</h3>
                    <p class="text-xs text-mocha mt-0.5">
                        الطلب: <strong class="text-espresso" id="cancelModalOrderNumber"></strong> &bull; <span id="cancelModalCustomerName"></span>
                    </p>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-linen border border-sand text-mocha text-xs leading-relaxed space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-espresso">
                    <span>🔄</span>
                    <span>مزامنة المخزون التلقائية:</span>
                </div>
                <p>
                    بمجرد تأكيد الإلغاء، سيقوم النظام تلقائياً بإعادة كامل كميات القطع المحجوزة في هذا الطلب إلى المخزون المتاح للبيع فوراً.
                </p>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-medium text-espresso">سبب الإلغاء أو ملاحظة الإدارة (اختياري):</label>
                <input type="text" 
                    id="cancelModalReasonInput" 
                    value="تم الإلغاء واسترجاع المخزون"
                    placeholder="مثال: لم يرد العميل، طلب العميل الإلغاء..." 
                    class="w-full px-4 py-2.5 text-xs rounded-input bg-vanilla border border-sand text-espresso focus:outline-none focus:border-action focus:bg-cream">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" 
                    id="confirmCancelBtn"
                    onclick="window.confirmCancelOrder()"
                    class="flex-1 py-3 px-4 rounded-btn bg-status-danger hover:opacity-90 text-cream text-xs font-medium transition-all shadow-xs cursor-pointer flex items-center justify-center gap-2">
                    <span>🗑️</span>
                    <span>تأكيد الإلغاء واسترجاع المخزون</span>
                </button>

                <button type="button" 
                    onclick="window.closeCancelModal()" 
                    class="btn-atelier-ghost text-xs cursor-pointer">
                    تراجع
                </button>
            </div>

        </div>
    </div>

</div>

<!-- Floating Toast Notification Container -->
<div id="ajaxToast" 
     class="fixed top-6 left-1/2 -translate-x-1/2 z-[99999] transform transition-all duration-300 -translate-y-24 opacity-0 pointer-events-none flex items-center gap-3.5 px-6 py-4 rounded-2xl shadow-xl border text-sm font-medium bg-cream text-espresso border-sand min-w-[320px] max-w-lg" 
     dir="rtl">
    <div id="ajaxToastIconWrap" class="w-10 h-10 rounded-xl bg-linen text-status-success flex items-center justify-center text-xl shrink-0 border border-sand">
        <span id="ajaxToastIcon">✅</span>
    </div>
    <div class="flex-1">
        <div id="ajaxToastTitle" class="text-xs font-bold text-status-success">تحديث ناجح</div>
        <div id="ajaxToastMsg" class="text-xs sm:text-sm font-medium text-mocha leading-snug mt-0.5">تم التحديث بنجاح</div>
    </div>
    <button type="button" onclick="window.hideInlineToast()" class="text-taupe hover:text-espresso text-base font-bold p-1 cursor-pointer transition-colors">✕</button>
</div>

@push('scripts')
<script>
let currentCancelOrderId = null;
let currentCancelSelectEl = null;
let currentPrevStatus = 'pending';

// Hide Toast Helper
window.hideInlineToast = function() {
    const toast = document.getElementById('ajaxToast');
    if (!toast) return;
    toast.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
    toast.classList.add('-translate-y-24', 'opacity-0', 'pointer-events-none');
};

// Toast Notification Helper
function showInlineToast(msg, type = 'success') {
    const toast = document.getElementById('ajaxToast');
    const toastMsg = document.getElementById('ajaxToastMsg');
    const toastTitle = document.getElementById('ajaxToastTitle');
    const toastIcon = document.getElementById('ajaxToastIcon');
    const toastIconWrap = document.getElementById('ajaxToastIconWrap');
    if (!toast) return;

    if (type === 'success') {
        toast.className = 'fixed top-6 left-1/2 -translate-x-1/2 z-[99999] transform transition-all duration-300 translate-y-0 opacity-100 pointer-events-auto flex items-center gap-3.5 px-6 py-4 rounded-2xl shadow-xl border text-sm font-medium bg-cream text-espresso border-sand min-w-[320px] max-w-lg';
        if (toastTitle) {
            toastTitle.textContent = '✓ تم التحديث بنجاح';
            toastTitle.className = 'text-xs font-bold text-status-success';
        }
        if (toastIconWrap) {
            toastIconWrap.className = 'w-10 h-10 rounded-xl bg-linen text-status-success flex items-center justify-center text-xl shrink-0 border border-sand';
        }
        if (toastIcon) toastIcon.textContent = '✅';
    } else {
        toast.className = 'fixed top-6 left-1/2 -translate-x-1/2 z-[99999] transform transition-all duration-300 translate-y-0 opacity-100 pointer-events-auto flex items-center gap-3.5 px-6 py-4 rounded-2xl shadow-xl border text-sm font-medium bg-cream text-espresso border-sand min-w-[320px] max-w-lg';
        if (toastTitle) {
            toastTitle.textContent = '⚠️ تنبيه خطأ';
            toastTitle.className = 'text-xs font-bold text-status-danger';
        }
        if (toastIconWrap) {
            toastIconWrap.className = 'w-10 h-10 rounded-xl bg-linen text-status-danger flex items-center justify-center text-xl shrink-0 border border-sand';
        }
        if (toastIcon) toastIcon.textContent = '❌';
    }

    if (toastMsg) toastMsg.textContent = msg;

    if (window._toastTimeout) clearTimeout(window._toastTimeout);
    window._toastTimeout = setTimeout(() => {
        window.hideInlineToast();
    }, 4500);
}

// AJAX Status Update Method
async function executeOrderStatusUpdate(orderId, newStatus, cancelReason = null) {
    const selectEl = document.getElementById('order_select_' + orderId);
    const spinner = document.getElementById('order_spinner_' + orderId);
    const badge = document.getElementById('order_badge_' + orderId);
    const prevStatus = selectEl ? (selectEl.getAttribute('data-prev-status') || 'pending') : 'pending';
    const updateUrl = selectEl ? selectEl.getAttribute('data-update-url') : `/admin/orders/${orderId}/status`;

    if (selectEl) selectEl.disabled = true;
    if (spinner) spinner.classList.remove('hidden');

    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(updateUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                status: newStatus,
                cancel_reason: cancelReason || 'تم التحديث عبر لوحة التحكم'
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Update badge text and color class smoothly
            if (badge) {
                badge.className = `px-2.5 py-1 rounded-pill border text-[11px] bg-linen text-status-success border-sand font-medium inline-block transition-all duration-300 scale-105`;
                badge.textContent = data.status_label || newStatus;
                setTimeout(() => badge.classList.remove('scale-105'), 300);
            }

            // Update data-prev-status
            if (selectEl) {
                selectEl.setAttribute('data-prev-status', newStatus);
                selectEl.value = newStatus;
            }

            showInlineToast(data.message || 'تم تحديث حالة الطلب بنجاح', 'success');
        } else {
            throw new Error(data.message || 'فشل تحديث حالة الطلب');
        }
    } catch (err) {
        console.error('Order update error:', err);
        if (selectEl) selectEl.value = prevStatus;
        showInlineToast(err.message || 'حدث خطأ أثناء تحديث حالة الطلب، يرجى المحاولة مرة أخرى.', 'error');
    } finally {
        if (selectEl) selectEl.disabled = false;
        if (spinner) spinner.classList.add('hidden');
    }
}

// Handle Select Change
window.handleStatusChange = function(selectEl, orderId, orderNumber, customerName) {
    const newStatus = selectEl.value;
    const prevStatus = selectEl.getAttribute('data-prev-status') || 'pending';

    if (newStatus === 'cancelled') {
        window.openCancelModal(orderId, orderNumber, customerName, selectEl, prevStatus);
    } else {
        executeOrderStatusUpdate(orderId, newStatus);
    }
};

window.openCancelModal = function(orderId, orderNumber, customerName, selectEl, prevStatus) {
    currentCancelOrderId = orderId;
    currentCancelSelectEl = selectEl;
    currentPrevStatus = prevStatus;

    const numEl = document.getElementById('cancelModalOrderNumber');
    const custEl = document.getElementById('cancelModalCustomerName');
    const inputEl = document.getElementById('cancelModalReasonInput');
    const modal = document.getElementById('luxuryCancelOrderModal');
    const card = document.getElementById('luxuryCancelOrderCard');

    if (numEl) numEl.textContent = orderNumber;
    if (custEl) custEl.textContent = customerName;
    if (inputEl) inputEl.value = 'تم الإلغاء واسترجاع المخزون';

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    });
};

window.closeCancelModal = function() {
    if (currentCancelSelectEl) {
        currentCancelSelectEl.value = currentPrevStatus;
    }
    const modal = document.getElementById('luxuryCancelOrderModal');
    const card = document.getElementById('luxuryCancelOrderCard');
    if (!modal) return;

    modal.classList.remove('opacity-100', 'pointer-events-auto');
    modal.classList.add('opacity-0', 'pointer-events-none');
    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);

    currentCancelOrderId = null;
    currentCancelSelectEl = null;
};

window.confirmCancelOrder = async function() {
    if (!currentCancelOrderId) return;
    const customReason = document.getElementById('cancelModalReasonInput')?.value || 'تم الإلغاء واسترجاع المخزون';
    const orderId = currentCancelOrderId;

    // Close modal right away
    const modal = document.getElementById('luxuryCancelOrderModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    await executeOrderStatusUpdate(orderId, 'cancelled', customReason);
};

window.previewReceipt = function(imgUrl, orderId, reference) {
    const modal = document.getElementById('receiptModal');
    const img = document.getElementById('receiptModalImg');
    const title = document.getElementById('receiptModalTitle');
    const ref = document.getElementById('receiptModalRef');

    if (img) img.src = imgUrl;
    if (title) title.textContent = 'إشعار التحويل - طلب ' + orderId;
    if (ref) {
        ref.textContent = reference ? 'الرقم المرجعي للعملية: ' + reference : 'لا يوجد رقم مرجعي مدخل';
    }

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

window.closeReceiptModal = function() {
    const modal = document.getElementById('receiptModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};
</script>
@endpush
@endsection