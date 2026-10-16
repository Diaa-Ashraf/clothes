@extends('layouts.admin')

@section('title', 'تفاصيل الطلب ' . ($order->public_order_id ?? $order->order_number ?? '#' . $order->id))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-clay hover:underline flex items-center gap-1">
                    <span>&larr;</span>
                    <span>العودة لقائمة الطلبات</span>
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-espresso flex items-center gap-3">
                <span>{{ $order->public_order_id ?? $order->order_number ?? '#' . $order->id }}</span>
            </h1>
            <p class="text-xs text-mocha mt-1 font-mono">
                تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }} ({{ $order->created_at->diffForHumans() }})
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Thermal POS Print Button -->
            <button type="button" 
                    onclick="window.printOrderReceipt()"
                    class="px-4 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-bold border border-sand/60 transition-all flex items-center gap-2 shadow-2xs cursor-pointer">
                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>طباعة إيصال الفاتورة (80mm)</span>
            </button>

            @if($order->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1eb857] text-white text-xs font-bold transition-all flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    <span>محادثة العميل واتساب</span>
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 2 Column Grid: Info & Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Customer, Delivery, Payment & Products -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Delivery & Payment Summary Card -->
            <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-espresso flex items-center gap-2 border-b border-sand/30 pb-3">
                    <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>طريقة الشحن والاستلام والدفع</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <!-- Delivery Type -->
                    <div class="p-3.5 rounded-xl bg-vanilla/40 border border-sand/40 space-y-1">
                        <span class="text-mocha text-[11px] block">نوع الاستلام والشحن:</span>
                        @if($order->delivery_type === 'pickup')
                            <div class="font-bold text-amber-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>استلام شخصي من المتجر / الفرع</span>
                            </div>
                            @if($order->branch)
                                <p class="text-[11px] text-espresso font-semibold mt-1">
                                    {{ $order->branch->name }}
                                </p>
                                <p class="text-[10px] text-taupe">
                                    {{ $order->branch->address }}
                                </p>
                            @endif
                        @else
                            <div class="font-bold text-espresso flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-action" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>شحن وتوصيل للعنوان (شركة الشحن)</span>
                            </div>
                            <p class="text-[11px] text-mocha mt-1">
                                خدمة الشحن السريع لباب العميل
                            </p>
                        @endif
                    </div>

                    <!-- Payment Method -->
                    <div class="p-3.5 rounded-xl bg-vanilla/40 border border-sand/40 space-y-1">
                        <span class="text-mocha text-[11px] block">طريقة الدفع:</span>
                        @if($order->payment_method === 'instapay')
                            <div class="font-bold text-clay flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>تحويل إلكتروني / إنستاباي (InstaPay)</span>
                            </div>
                            @if($order->instapay_reference)
                                <p class="text-[11px] font-mono text-espresso mt-1">
                                    مرجع التحويل: <strong>{{ $order->instapay_reference }}</strong>
                                </p>
                            @endif
                        @else
                            <div class="font-bold text-emerald-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>الدفع نقداً عند الاستلام (COD)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-espresso flex items-center gap-2 border-b border-sand/30 pb-3">
                    <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>بيانات العميل</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-mocha block mb-1">اسم العميل:</span>
                        <span class="font-bold text-espresso text-sm">{{ $order->customer_name ?: ($order->user->name ?? 'عميل زائر') }}</span>
                        @if($order->user_id)
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-linen text-mocha border border-sand/40">
                                عميل مسجل (#{{ $order->user_id }})
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-mocha block mb-1">رقم الهاتف:</span>
                        <span class="font-bold text-espresso font-mono text-sm" dir="ltr">{{ $order->phone ?: 'غير محدد' }}</span>
                    </div>

                    @if($order->delivery_type !== 'pickup')
                        <div class="sm:col-span-2">
                            <span class="text-mocha block mb-1">عنوان التوصيل والشحن:</span>
                            <span class="font-medium text-espresso leading-relaxed bg-vanilla/40 p-3 rounded-xl block border border-sand/40">
                                {{ $order->address ?: 'غير محدد' }}
                            </span>
                        </div>
                    @endif

                    @if($order->notes)
                        <div class="sm:col-span-2">
                            <span class="text-mocha block mb-1">ملاحظات العميل:</span>
                            <span class="font-medium text-amber-900 leading-relaxed bg-amber-50 p-3 rounded-xl block border border-amber-200">
                                {{ $order->notes }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ordered Items Card -->
            <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-espresso flex items-center gap-2 border-b border-sand/30 pb-3">
                    <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>قطع الأزياء المطلوبة ({{ $order->orderItems->count() }})</span>
                </h3>

                <div class="divide-y divide-sand/30">
                    @foreach($order->orderItems as $item)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-14 rounded-xl bg-vanilla border border-sand/50 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs text-taupe font-bold">👗</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-espresso flex items-center gap-2">
                                        <span>{{ $item->product->name ?? 'منتج محذوف' }}</span>
                                        @if($item->portion_label)
                                            <span class="px-1.5 py-0.5 rounded-md bg-linen border border-sand/40 text-mocha text-[10px] font-bold">
                                                {{ $item->portion_label }}
                                            </span>
                                        @endif
                                    </h4>
                                    @if($item->notes)
                                        <p class="text-[10px] text-amber-900 bg-amber-50 px-2 py-0.5 rounded-md mt-0.5 inline-block">
                                            ملاحظة العميل: {{ $item->notes }}
                                        </p>
                                    @endif
                                    <span class="text-[11px] text-mocha block mt-0.5 font-mono">
                                        {{ format_currency($item->price) }} × {{ $item->quantity }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-end">
                                <span class="text-xs font-bold text-espresso tabular-nums font-mono">
                                    {{ format_currency($item->total_price ?? ($item->price * $item->quantity)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-sand/40 pt-4 flex items-center justify-between text-xs">
                    <span class="font-bold text-espresso text-sm">الإجمالي النهائي للطلب:</span>
                    <span class="text-lg font-bold text-clay tabular-nums font-mono">
                        {{ format_currency($order->total_price ?? 0) }}
                    </span>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Status & Actions -->
        <div class="space-y-6">
            
            <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-espresso flex items-center gap-2 border-b border-sand/30 pb-3">
                    <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>إدارة حالة الطلب والمخزون</span>
                </h3>

                @php
                    $statusColors = [
                        'under_review' => 'bg-amber-100 text-amber-900 border-amber-300 font-bold',
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'processing' => 'bg-linen text-mocha border-sand/60 font-semibold',
                        'ready_for_pickup' => 'bg-cyan-50 text-cyan-800 border-cyan-200 font-bold',
                        'on_the_way' => 'bg-clay/10 text-clay border-clay/30 font-bold',
                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200 font-bold',
                        'cancelled' => 'bg-rose-50 text-rose-800 border-rose-200 font-bold',
                    ];
                    $statusLabels = [
                        'under_review' => 'قيد المراجعة الهاتفية',
                        'pending' => 'طلب جديد قيد الانتظار',
                        'confirmed' => 'تم التأكيد',
                        'processing' => 'قيد تجهيز القطع والتغليف',
                        'ready_for_pickup' => 'جاهز للاستلام بالفرع',
                        'on_the_way' => 'في الطريق مع مندوب الشحن',
                        'shipped' => 'تم الشحن والتسليم لشركة الشحن',
                        'delivered' => 'تم التسليم واستلام المبلغ',
                        'cancelled' => 'ملغي (تم استرجاع المخزون)',
                    ];
                    $colorClass = $statusColors[$order->status] ?? 'bg-linen text-mocha border-sand/40';
                    $label = $statusLabels[$order->status] ?? $order->status;
                @endphp

                <div class="p-3.5 rounded-xl border {{ $colorClass }} space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] text-mocha">الحالة الحالية:</span>
                        <span class="font-bold text-xs">{{ $label }}</span>
                    </div>
                    @if($order->status === 'cancelled')
                        <span class="text-[10px] text-rose-700 font-bold block pt-1 border-t border-rose-200">
                            تم استرجاع كميات المخزون المحجوزة تلقائياً
                        </span>
                    @else
                        <span class="text-[10px] text-emerald-700 font-bold block pt-1 border-t border-emerald-200">
                            الكميات مخصومة ومحجوزة للطلب
                        </span>
                    @endif
                </div>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[11px] font-bold text-espresso mb-1">تحديث الحالة:</label>
                        <select name="status" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/40 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                            <option value="under_review" {{ $order->status === 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>تم التأكيد</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>قيد التجهيز والتغليف</option>
                            @if($order->delivery_type === 'pickup')
                                <option value="ready_for_pickup" {{ $order->status === 'ready_for_pickup' ? 'selected' : '' }}>جاهز للاستلام بالفرع</option>
                            @else
                                <option value="on_the_way" {{ $order->status === 'on_the_way' ? 'selected' : '' }}>في الطريق مع المندوب</option>
                            @endif
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التسليم بنجاح</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>إلغاء الطلب واسترجاع المخزون</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-espresso mb-1">سبب الإلغاء / ملاحظة الإدارة (اختياري):</label>
                        <textarea name="cancel_reason" rows="2" class="w-full p-2.5 text-xs rounded-xl bg-vanilla/40 border border-sand/60 text-espresso focus:outline-none focus:border-action" placeholder="مثال: لم يرد العميل، طلب العميل الإلغاء...">{{ $order->cancel_reason }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold transition-all shadow-sm cursor-pointer">
                        حفظ التغييرات ومزامنة المخزون
                    </button>
                </form>
            </div>

            <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-3">
                <h4 class="text-xs font-bold text-rose-600">حذف الطلب:</h4>
                <p class="text-[11px] text-mocha leading-relaxed">
                    عند حذف الطلب سيتم استرجاع المخزون تلقائياً للمنتجات إذا لم يكن ملغياً بالفعل.
                </p>
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الطلب نهائياً؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-all cursor-pointer">
                        حذف الطلب نهائياً
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

<!-- Thermal Receipt Print Container (Hidden from Screen, Visible on Print) -->
<div id="thermal-receipt" class="hidden">
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }
            #thermal-receipt, #thermal-receipt * {
                visibility: visible !important;
            }
            #thermal-receipt {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 10px !important;
                font-family: 'Courier New', monospace, sans-serif !important;
                font-size: 11px !important;
                color: #000 !important;
                background: #fff !important;
                direction: rtl !important;
                text-align: right !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>

    <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 8px; margin-bottom: 8px;">
        <h2 style="font-size: 16px; font-weight: 900; margin: 0 0 4px 0;">متجر الأزياء والموضة الراقية</h2>
        <p style="font-size: 10px; margin: 0;">أرقى تشكيلات الملابس والإكسسوارات</p>
        <p style="font-size: 10px; margin: 2px 0 0 0;">خدمة العملاء والشحن</p>
    </div>

    <div style="font-size: 11px; margin-bottom: 8px; border-bottom: 1px dashed #000; padding-bottom: 6px;">
        <div><strong>رقم الطلب:</strong> {{ $order->public_order_id ?? $order->order_number ?? '#' . $order->id }}</div>
        <div><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
        <div><strong>العميل:</strong> {{ $order->customer_name ?: ($order->user->name ?? 'زائر') }}</div>
        <div><strong>الهاتف:</strong> {{ $order->phone ?: '-' }}</div>
        @if($order->delivery_type === 'pickup')
            <div><strong>الاستلام:</strong> استلام من الفرع ({{ $order->branch->name ?? 'الرئيسي' }})</div>
        @else
            <div><strong>الاستلام:</strong> توصيل للعنوان</div>
            <div><strong>العنوان:</strong> {{ $order->address ?: '-' }}</div>
        @endif
        <div><strong>الدفع:</strong> {{ $order->payment_method === 'instapay' ? 'إنستاباي' : 'نقداً عند الاستلام' }}</div>
    </div>

    <div style="margin-bottom: 8px; border-bottom: 1px dashed #000; padding-bottom: 6px;">
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #000; text-align: right;">
                    <th style="padding: 2px 0;">القطعة</th>
                    <th style="padding: 2px 0; text-align: center;">الكمية</th>
                    <th style="padding: 2px 0; text-align: left;">المجموع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td style="padding: 3px 0; vertical-align: top;">
                            <strong>{{ $item->product->name ?? 'قطعة أزياء' }}</strong>
                            @if($item->portion_label)
                                <div style="font-size: 9px;">({{ $item->portion_label }})</div>
                            @endif
                            @if($item->notes)
                                <div style="font-size: 9px; font-style: italic;">* {{ $item->notes }}</div>
                            @endif
                        </td>
                        <td style="padding: 3px 0; text-align: center; vertical-align: top;">{{ $item->quantity }}</td>
                        <td style="padding: 3px 0; text-align: left; vertical-align: top; white-space: nowrap;">{{ number_format($item->total_price ?? ($item->price * $item->quantity), 2) }} ج.م</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="font-size: 12px; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 6px;">
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 13px;">
            <span>الإجمالي المطلوب:</span>
            <span>{{ number_format($order->total_price ?? 0, 2) }} ج.م</span>
        </div>
    </div>

    <div style="text-align: center; font-size: 10px;">
        <p style="margin: 0;">شكراً لاختياركم متجرنا</p>
        <p style="margin: 2px 0 0 0;">نتمنى لكم تجربة تسوق راقية دائماً</p>
    </div>
</div>

<script>
    window.printOrderReceipt = function() {
        window.print();
    };
</script>
@endsection