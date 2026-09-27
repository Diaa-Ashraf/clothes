@extends('layouts.admin')

@section('title', __('تسجيل طلب يدوي جديد (واتساب / هاتف / فرع)'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="orderCreator()">

    <!-- 1. Header Bar -->
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                    <span>←</span>
                    <span>{{ __('العودة لقائمة الطلبات') }}</span>
                </a>
                <span class="text-gray-300">•</span>
                <span class="text-xs font-bold text-orange-600">تسجيل طلبات العامرية المباشرة</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('إنشاء وتسجيل طلب جديد') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                {{ __('تسجيل طلبات الواتساب، الهاتف، أو طلبات الشراء المباشرة من فروع العامرية وتحديث المخزون آلياً.') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-200 text-red-700 text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>المخزون متزامن لحظياً</span>
            </span>
        </div>
    </div>

    @if($errors->any())
        <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1.5 shadow-xs">
            <div class="font-black text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ __('يرجى تصحيح الأخطاء التالية قبل المتابعة:') }}</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left 8 cols: Order Source, Customer Info, Items Repeater -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. Order Source Selection -->
                <div class="bg-white border border-orange-100 rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-3">
                        <h3 class="font-black text-sm text-gray-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xs">1</span>
                            <span>{{ __('مصدر الطلب وطريقة التواصل') }}</span>
                        </h3>
                        <span class="text-[11px] text-gray-400 font-bold">تحديد قناة استلام الطلب</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-2xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'whatsapp' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900 ring-2 ring-emerald-500/20 shadow-xs' : 'border-orange-100 bg-canvas-base/50 hover:bg-orange-50/50 text-gray-700'">
                            <input type="radio" name="order_source" value="whatsapp" x-model="orderSource" class="sr-only">
                            <span class="text-2xl mb-1">💬</span>
                            <span class="text-xs font-black">واتساب مباشر</span>
                            <span class="text-[10px] text-gray-500">رسائل WhatsApp</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-2xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'phone' ? 'border-red-500 bg-red-50/50 text-red-900 ring-2 ring-red-500/20 shadow-xs' : 'border-orange-100 bg-canvas-base/50 hover:bg-orange-50/50 text-gray-700'">
                            <input type="radio" name="order_source" value="phone" x-model="orderSource" class="sr-only">
                            <span class="text-2xl mb-1">📞</span>
                            <span class="text-xs font-black">مكالمة هاتفية</span>
                            <span class="text-[10px] text-gray-500">اتصال مباشر</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-2xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'branch' ? 'border-blue-500 bg-blue-50/50 text-blue-900 ring-2 ring-blue-500/20 shadow-xs' : 'border-orange-100 bg-canvas-base/50 hover:bg-orange-50/50 text-gray-700'">
                            <input type="radio" name="order_source" value="branch" x-model="orderSource" class="sr-only">
                            <span class="text-2xl mb-1">🏪</span>
                            <span class="text-xs font-black">كاشير / الفرع</span>
                            <span class="text-[10px] text-gray-500">استلام فوري</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-2xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'admin' ? 'border-purple-500 bg-purple-50/50 text-purple-900 ring-2 ring-purple-500/20 shadow-xs' : 'border-orange-100 bg-canvas-base/50 hover:bg-orange-50/50 text-gray-700'">
                            <input type="radio" name="order_source" value="admin" x-model="orderSource" class="sr-only">
                            <span class="text-2xl mb-1">🖥️</span>
                            <span class="text-xs font-black">إداري يدوي</span>
                            <span class="text-[10px] text-gray-500">لوحة التحكم</span>
                        </label>
                    </div>
                </div>

                <!-- 2. Customer Information -->
                <div class="bg-white border border-orange-100 rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-3">
                        <h3 class="font-black text-sm text-gray-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xs">2</span>
                            <span>{{ __('بيانات العميل وعنوان التوصيل') }}</span>
                        </h3>
                        <span class="text-[11px] text-gray-400 font-bold">منطقة العامرية والإسكندرية</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-1.5">
                                {{ __('اسم العميل الكامل') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name') }}"
                                   placeholder="مثال: الحاج أحمد محمود"
                                   class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-1.5">
                                {{ __('رقم الهاتف / الواتساب') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" required dir="ltr" value="{{ old('phone') }}"
                                   placeholder="010XXXXXXXX"
                                   class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all text-right">
                        </div>
                    </div>

                    <!-- Delivery Type Options -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-bold text-gray-900">
                            {{ __('نوع الاستلام والتوصيل') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all"
                                   :class="deliveryType === 'delivery' ? 'border-red-500 bg-red-50/40 text-red-900 ring-2 ring-red-500/20' : 'border-orange-100 bg-canvas-base/50 text-gray-700'">
                                <input type="radio" name="delivery_type" value="delivery" x-model="deliveryType" class="sr-only">
                                <span class="text-xl">🛵</span>
                                <div>
                                    <span class="text-xs font-black block">توصيل منزلي سريع بالعامرية</span>
                                    <span class="text-[10px] text-gray-500">مع مندوب توصيل المتجر</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all"
                                   :class="deliveryType === 'pickup' ? 'border-red-500 bg-red-50/40 text-red-900 ring-2 ring-red-500/20' : 'border-orange-100 bg-canvas-base/50 text-gray-700'">
                                <input type="radio" name="delivery_type" value="pickup" x-model="deliveryType" class="sr-only">
                                <span class="text-xl">🏬</span>
                                <div>
                                    <span class="text-xs font-black block">استلام مباشر من أحد الفروع</span>
                                    <span class="text-[10px] text-gray-500">تجهيز الطلب لاستلام الزبون</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Address Input (when delivery) -->
                    <div x-show="deliveryType === 'delivery'" x-transition class="space-y-1.5 pt-2">
                        <label class="block text-xs font-bold text-gray-900">
                            {{ __('عنوان التوصيل بالتفصيل') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" rows="2"
                                  placeholder="مثال: العامرية، شارع السوق التجاري، بجوار مسجد التوحيد، عمارة 4 شقة 2"
                                  class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">{{ old('address') }}</textarea>
                    </div>

                    <!-- Branch Selection -->
                    <div class="space-y-1.5 pt-2">
                        <label class="block text-xs font-bold text-gray-900">
                            {{ __('الفرع المسؤول عن التجهيز أو الاستلام') }}
                        </label>
                        <select name="branch_id" 
                                class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                            <option value="">-- توصيل مركزي / أو اختر فرع العامرية المعني --</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>
                                    🏬 {{ $b->name }} ({{ $b->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 3. Dynamic Products Repeater -->
                <div class="bg-white border border-orange-100 rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-3">
                        <div>
                            <h3 class="font-black text-sm text-gray-900 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xs">3</span>
                                <span>{{ __('أصناف ومنتجات الطلب') }}</span>
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ __('اختر الأصناف، حدد الأوزان أو الكميات، والأسعار') }}</p>
                        </div>

                        <button type="button" 
                                @click="addItem()" 
                                class="px-3.5 py-2 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة صنف آخر</span>
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-4 rounded-2xl bg-canvas-base/60 border border-orange-100 space-y-3 transition-all hover:border-orange-200">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-orange-100 text-orange-800 flex items-center justify-center font-black text-xs" x-text="index + 1"></span>
                                        <span class="text-xs font-bold text-gray-700">بيانات الصنف</span>
                                    </div>

                                    <button type="button" 
                                            x-show="items.length > 1"
                                            @click="removeItem(index)" 
                                            class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline flex items-center gap-1 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>حذف الصنف</span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                    <!-- Product Select -->
                                    <div class="sm:col-span-6">
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            اختر المنتج <span class="text-red-500">*</span>
                                        </label>
                                        <select :name="'items[' + index + '][product_id]'" 
                                                x-model="item.product_id" 
                                                @change="onProductSelect(index)"
                                                required 
                                                class="w-full px-3 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                                            <option value="">-- اختر المنتج من القائمة --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" 
                                                        data-price="{{ $p->price }}" 
                                                        data-portion="{{ $p->portion_unit ?? 'piece' }}"
                                                        data-name="{{ $p->name }}">
                                                    {{ $p->name }} — ({{ number_format($p->price, 2) }} ج.م)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Portion / Weight Type -->
                                    <div class="sm:col-span-3">
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            نوع الوزن / التجزئة
                                        </label>
                                        <select :name="'items[' + index + '][portion_label]'" 
                                                x-model="item.portion_label"
                                                class="w-full px-3 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                                            <option value="قطعة / عبوة كاملة">قطعة / عبوة</option>
                                            <option value="1 كيلو">1 كيلو</option>
                                            <option value="نصف كيلو (500 جم)">نصف كيلو (500 جم)</option>
                                            <option value="ربع كيلو (250 جم)">ربع كيلو (250 جم)</option>
                                            <option value="تمن كيلو (125 جم)">ثمن كيلو (125 جم)</option>
                                            <option value="وزن حسب الطلب">وزن حسب الطلب</option>
                                        </select>
                                    </div>

                                    <!-- Quantity / Weight Input -->
                                    <div class="sm:col-span-3">
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            الكمية / العدد <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               step="0.05" 
                                               min="0.05"
                                               :name="'items[' + index + '][quantity]'" 
                                               x-model.number="item.quantity" 
                                               required
                                               class="w-full px-3 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 text-xs font-bold text-center focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center pt-1 border-t border-orange-100/60">
                                    <!-- Unit Price -->
                                    <div class="sm:col-span-4">
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">سعر الوحدة (ج.م)</label>
                                        <input type="number" 
                                               step="0.5" 
                                               :name="'items[' + index + '][price]'" 
                                               x-model.number="item.price" 
                                               required
                                               class="w-full px-3 py-1.5 rounded-lg border border-orange-100 bg-white text-gray-900 text-xs font-bold focus:outline-none">
                                    </div>

                                    <!-- Notes for this item -->
                                    <div class="sm:col-span-5">
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">ملاحظة تقطيع / تجهيز (اختياري)</label>
                                        <input type="text" 
                                               :name="'items[' + index + '][notes]'" 
                                               x-model="item.notes" 
                                               placeholder="مثال: تقطيع شرائح رفيعة، جبنة قديمة..."
                                               class="w-full px-3 py-1.5 rounded-lg border border-orange-100 bg-white text-gray-900 text-xs focus:outline-none">
                                    </div>

                                    <!-- Line Subtotal -->
                                    <div class="sm:col-span-3 text-start sm:text-end pt-2 sm:pt-0">
                                        <span class="text-[10px] text-gray-500 block">الإجمالي الفرعي:</span>
                                        <span class="text-sm font-black text-red-600 font-sans" x-text="((item.quantity || 0) * (item.price || 0)).toFixed(2) + ' ج.م'"></span>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <div class="pt-2 flex justify-start">
                        <button type="button" 
                                @click="addItem()" 
                                class="px-4 py-2 rounded-xl bg-orange-50 hover:bg-orange-100 border border-orange-200 text-red-700 text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة صنف إضافي للطلب</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right 4 cols: Payment, Status, Grand Total, Submission -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Order Settings & Status -->
                <div class="bg-white border border-orange-100 rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-black text-sm text-gray-900 border-b border-orange-100 pb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xs">4</span>
                        <span>{{ __('حالة الطلب والدفع') }}</span>
                    </h3>

                    <!-- Order Status -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">
                            {{ __('حالة الطلب الأولية') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                            <option value="confirmed" selected> تم التأكيد وجاهز للتجهيز (Confirmed)</option>
                            <option value="processing"> قيد التجهيز بالمحل (Processing)</option>
                            <option value="ready_for_pickup"> جاهز للاستلام بالفرع (Ready for Pickup)</option>
                            <option value="on_the_way">🛵 مع مندوب التوصيل (On the Way)</option>
                            <option value="delivered"> تم التسليم للعميل (Delivered)</option>
                            <option value="pending">⏳ قيد الانتظار (Pending)</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">
                            {{ __('طريقة الدفع') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                            <option value="cash_on_delivery" selected>💵 الدفع نقدياً عند الاستلام (Cash)</option>
                            <option value="instapay">📱 تحويل إنستاباي (InstaPay)</option>
                            <option value="cash_in_branch">🏪 كاش داخل الفرع</option>
                            <option value="vodafone_cash">📱 فودافون كاش / محفظة إلكترونية</option>
                        </select>
                    </div>

                    <!-- General Notes -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">
                            {{ __('ملاحظات عامة حول الطلب') }}
                        </label>
                        <textarea name="notes" rows="3"
                                  placeholder="أي تعليمات إضافية، موعد التسليم المطلوب، تفاصيل محادثة الواتساب..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Grand Total & Submit Card -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-3xl p-6 shadow-md space-y-4">
                    <h3 class="text-xs font-black text-orange-400 uppercase tracking-wider">
                        ملخص الحساب الإجمالي
                    </h3>

                    <div class="space-y-2 border-b border-gray-700/80 pb-4 text-xs">
                        <div class="flex justify-between text-gray-300">
                            <span>عدد الأصناف:</span>
                            <span class="font-bold" x-text="items.length + ' صنف'"></span>
                        </div>
                        <div class="flex justify-between text-gray-300">
                            <span>إجمالي المنتجات:</span>
                            <span class="font-bold font-sans" x-text="grandTotal.toFixed(2) + ' ج.م'"></span>
                        </div>
                        <div class="flex justify-between text-gray-300">
                            <span>خدمة التوصيل:</span>
                            <span class="text-emerald-400 font-bold" x-text="deliveryType === 'delivery' ? 'حسب المنطقة' : 'مجاناً (استلام فرع)'"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] text-gray-400 block mb-1">المبلغ المطلوب تحصيله:</span>
                        <div class="text-3xl font-black text-white font-sans tracking-tight" x-text="grandTotal.toFixed(2) + ' ج.م'"></div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-sm shadow-lg shadow-red-500/30 transition-all transform active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>تسجيل وحفظ الطلب الآن</span>
                        </button>

                        <a href="{{ route('admin.orders.index') }}" 
                           class="w-full py-2.5 px-4 rounded-xl border border-gray-700 hover:bg-gray-800 text-gray-300 hover:text-white text-xs font-bold text-center block transition-colors">
                            {{ __('إلغاء والعودة') }}
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

@push('scripts')
<script>
    function orderCreator() {
        return {
            orderSource: 'whatsapp',
            deliveryType: 'delivery',
            items: [
                {
                    product_id: '',
                    portion_label: 'قطعة / عبوة كاملة',
                    quantity: 1,
                    price: 0,
                    notes: ''
                }
            ],
            addItem() {
                this.items.push({
                    product_id: '',
                    portion_label: 'قطعة / عبوة كاملة',
                    quantity: 1,
                    price: 0,
                    notes: ''
                });
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            onProductSelect(index) {
                const item = this.items[index];
                const selectEl = document.querySelectorAll('select[name^="items"][name$="[product_id]"]')[index];
                if (!selectEl) return;

                const selectedOption = selectEl.options[selectEl.selectedIndex];
                if (selectedOption && selectedOption.dataset.price) {
                    item.price = parseFloat(selectedOption.dataset.price) || 0;
                }
            },
            get grandTotal() {
                return this.items.reduce((sum, item) => {
                    const qty = parseFloat(item.quantity) || 0;
                    const pr = parseFloat(item.price) || 0;
                    return sum + (qty * pr);
                }, 0);
            }
        };
    }
</script>
@endpush
@endsection