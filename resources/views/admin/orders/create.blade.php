@extends('layouts.admin')

@section('title', 'تسجيل طلب يدوي جديد')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="orderCreator()">

    <!-- Header Bar -->
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-clay hover:underline flex items-center gap-1">
                    <span>←</span>
                    <span>العودة لقائمة الطلبات</span>
                </a>
                <span class="text-sand">•</span>
                <span class="text-xs font-semibold text-mocha">تسجيل طلبات المتجر المباشرة</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                إنشاء وتسجيل طلب جديد
            </h1>
            <p class="text-xs text-mocha mt-1">
                تسجيل طلبات الواتساب، الهاتف، أو الشراء المباشر من الفرع وحجز ومزامنة المخزون آلياً.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-linen border border-sand/40 text-espresso text-xs font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                <span>المخزون متزامن لحظياً</span>
            </span>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <span>يرجى مراجعة وتصحيح الملاحظات التالية:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
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
                <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-sand/30 pb-3">
                        <h3 class="font-bold text-xs text-espresso flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-action/10 text-action flex items-center justify-center text-xs font-bold">1</span>
                            <span>مصدر وقناة الطلب</span>
                        </h3>
                        <span class="text-[11px] text-taupe">تحديد طريقة استلام الطلب</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'whatsapp' ? 'border-action bg-vanilla text-espresso ring-1 ring-action/20 shadow-2xs' : 'border-sand/40 bg-vanilla/20 hover:bg-vanilla/40 text-mocha'">
                            <input type="radio" name="order_source" value="whatsapp" x-model="orderSource" class="sr-only">
                            <span class="text-xl mb-1">💬</span>
                            <span class="text-xs font-bold text-espresso">واتساب مباشر</span>
                            <span class="text-[10px] text-taupe">محادثة WhatsApp</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'phone' ? 'border-action bg-vanilla text-espresso ring-1 ring-action/20 shadow-2xs' : 'border-sand/40 bg-vanilla/20 hover:bg-vanilla/40 text-mocha'">
                            <input type="radio" name="order_source" value="phone" x-model="orderSource" class="sr-only">
                            <span class="text-xl mb-1">📞</span>
                            <span class="text-xs font-bold text-espresso">مكالمة هاتفية</span>
                            <span class="text-[10px] text-taupe">اتصال هاتفي</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'branch' ? 'border-action bg-vanilla text-espresso ring-1 ring-action/20 shadow-2xs' : 'border-sand/40 bg-vanilla/20 hover:bg-vanilla/40 text-mocha'">
                            <input type="radio" name="order_source" value="branch" x-model="orderSource" class="sr-only">
                            <span class="text-xl mb-1">🏬</span>
                            <span class="text-xs font-bold text-espresso">المعرض / الفرع</span>
                            <span class="text-[10px] text-taupe">شراء مباشر</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-xl border cursor-pointer transition-all text-center"
                               :class="orderSource === 'admin' ? 'border-action bg-vanilla text-espresso ring-1 ring-action/20 shadow-2xs' : 'border-sand/40 bg-vanilla/20 hover:bg-vanilla/40 text-mocha'">
                            <input type="radio" name="order_source" value="admin" x-model="orderSource" class="sr-only">
                            <span class="text-xl mb-1">🖥️</span>
                            <span class="text-xs font-bold text-espresso">لوحة الإدارة</span>
                            <span class="text-[10px] text-taupe">طلب خاص</span>
                        </label>
                    </div>
                </div>

                <!-- 2. Customer Information -->
                <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-sand/30 pb-3">
                        <h3 class="font-bold text-xs text-espresso flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-action/10 text-action flex items-center justify-center text-xs font-bold">2</span>
                            <span>بيانات العميل وعنوان التوصيل</span>
                        </h3>
                        <span class="text-[11px] text-taupe">معلومات الشحن</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-espresso mb-1.5">
                                اسم العميل الكامل <span class="text-clay">*</span>
                            </label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name') }}"
                                   placeholder="مثال: أحمد محمود"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-espresso mb-1.5">
                                رقم الهاتف / الواتساب <span class="text-clay">*</span>
                            </label>
                            <input type="text" name="phone" required dir="ltr" value="{{ old('phone') }}"
                                   placeholder="010XXXXXXXX"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action font-mono transition-all text-right">
                        </div>
                    </div>

                    <!-- Delivery Type Options -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-bold text-espresso">
                            نوع الاستلام والتوصيل <span class="text-clay">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
                                   :class="deliveryType === 'delivery' ? 'border-action bg-vanilla/60 text-espresso ring-1 ring-action/20' : 'border-sand/40 bg-vanilla/20 text-mocha'">
                                <input type="radio" name="delivery_type" value="delivery" x-model="deliveryType" class="sr-only">
                                <span class="text-xl">🚚</span>
                                <div>
                                    <span class="text-xs font-bold block text-espresso">شحن وتوصيل للعنوان</span>
                                    <span class="text-[10px] text-taupe">بواسطة شركة الشحن</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
                                   :class="deliveryType === 'pickup' ? 'border-action bg-vanilla/60 text-espresso ring-1 ring-action/20' : 'border-sand/40 bg-vanilla/20 text-mocha'">
                                <input type="radio" name="delivery_type" value="pickup" x-model="deliveryType" class="sr-only">
                                <span class="text-xl">🏬</span>
                                <div>
                                    <span class="text-xs font-bold block text-espresso">استلام من المعرض / الفرع</span>
                                    <span class="text-[10px] text-taupe">تجهيز القطع للاستلام</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Address Input (when delivery) -->
                    <div x-show="deliveryType === 'delivery'" x-transition class="space-y-1.5 pt-2">
                        <label class="block text-xs font-bold text-espresso">
                            عنوان الشحن بالتفصيل <span class="text-clay">*</span>
                        </label>
                        <textarea name="address" rows="2"
                                  placeholder="المدينة، الحي، اسم الشارع، رقم العمارة والشقة..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">{{ old('address') }}</textarea>
                    </div>

                    <!-- Branch Selection -->
                    @if($branches->isNotEmpty())
                        <div class="space-y-1.5 pt-2">
                            <label class="block text-xs font-bold text-espresso">
                                المعرض أو الفرع المسؤول
                            </label>
                            <select name="branch_id" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                                <option value="">-- المستودع الرئيسي الافتراضي --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>
                                        🏬 {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- 3. Dynamic Products Repeater -->
                <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-sand/30 pb-3">
                        <div>
                            <h3 class="font-bold text-xs text-espresso flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-action/10 text-action flex items-center justify-center text-xs font-bold">3</span>
                                <span>قطع وتشكيلات الأزياء المطلوبة</span>
                            </h3>
                            <p class="text-[11px] text-mocha mt-0.5">حدد القطع، المقاسات، الكميات، والأسعار</p>
                        </div>

                        <button type="button" 
                                @click="addItem()" 
                                class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige border border-sand/60 text-espresso text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة قطعة أخرى</span>
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-4 rounded-xl bg-vanilla/40 border border-sand/40 space-y-3">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md bg-linen border border-sand/50 text-mocha flex items-center justify-center font-bold text-[11px]" x-text="index + 1"></span>
                                        <span class="text-xs font-bold text-espresso">القطعة</span>
                                    </div>

                                    <button type="button" 
                                            x-show="items.length > 1"
                                            @click="removeItem(index)" 
                                            class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">
                                        حذف القطعة
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                    <!-- Product Select -->
                                    <div class="sm:col-span-6">
                                        <label class="block text-[11px] font-bold text-espresso mb-1">
                                            اختر القطعة <span class="text-clay">*</span>
                                        </label>
                                        <select :name="'items[' + index + '][product_id]'" 
                                                x-model="item.product_id" 
                                                @change="onProductSelect(index)"
                                                required 
                                                class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-cream text-espresso text-xs font-semibold focus:outline-none focus:border-action">
                                            <option value="">-- اختر قطعة الأزياء --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" 
                                                        data-price="{{ $p->price }}" 
                                                        data-name="{{ $p->name }}">
                                                    {{ $p->name }} — ({{ number_format($p->price, 2) }} ج.م)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Size & Color Specification -->
                                    <div class="sm:col-span-3">
                                        <label class="block text-[11px] font-bold text-espresso mb-1">
                                            المقاس واللون
                                        </label>
                                        <input type="text" 
                                               :name="'items[' + index + '][portion_label]'" 
                                               x-model="item.portion_label"
                                               placeholder="مثال: L / كحلي"
                                               class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-cream text-espresso text-xs focus:outline-none focus:border-action">
                                    </div>

                                    <!-- Quantity -->
                                    <div class="sm:col-span-3">
                                        <label class="block text-[11px] font-bold text-espresso mb-1">
                                            العدد / الكمية <span class="text-clay">*</span>
                                        </label>
                                        <input type="number" 
                                               min="1" 
                                               :name="'items[' + index + '][quantity]'" 
                                               x-model.number="item.quantity" 
                                               required
                                               class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-cream text-espresso text-xs font-bold text-center focus:outline-none focus:border-action">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center pt-2 border-t border-sand/30">
                                    <!-- Unit Price -->
                                    <div class="sm:col-span-4">
                                        <label class="block text-[10px] font-bold text-mocha mb-1">سعر القطعة (ج.م)</label>
                                        <input type="number" 
                                               step="0.01" 
                                               :name="'items[' + index + '][price]'" 
                                               x-model.number="item.price" 
                                               required
                                               class="w-full px-3 py-1.5 rounded-lg border border-sand/60 bg-cream text-espresso text-xs font-bold focus:outline-none">
                                    </div>

                                    <!-- Notes for this item -->
                                    <div class="sm:col-span-5">
                                        <label class="block text-[10px] font-bold text-mocha mb-1">ملاحظات (تغليف كهدية، مقاس خاص)</label>
                                        <input type="text" 
                                               :name="'items[' + index + '][notes]'" 
                                               x-model="item.notes" 
                                               placeholder="اختياري..."
                                               class="w-full px-3 py-1.5 rounded-lg border border-sand/60 bg-cream text-espresso text-xs focus:outline-none">
                                    </div>

                                    <!-- Line Subtotal -->
                                    <div class="sm:col-span-3 text-start sm:text-end pt-2 sm:pt-0">
                                        <span class="text-[10px] text-mocha block">الإجمالي:</span>
                                        <span class="text-sm font-bold text-clay font-mono" x-text="((item.quantity || 0) * (item.price || 0)).toFixed(2) + ' ج.م'"></span>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <div class="pt-2 flex justify-start">
                        <button type="button" 
                                @click="addItem()" 
                                class="px-4 py-2 rounded-xl bg-linen hover:bg-beige border border-sand/60 text-espresso text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة قطعة أخرى</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right 4 cols: Payment, Status, Grand Total, Submission -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Order Settings & Status -->
                <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-xs text-espresso border-b border-sand/30 pb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-action/10 text-action flex items-center justify-center text-xs font-bold">4</span>
                        <span>حالة الطلب والدفع</span>
                    </h3>

                    <!-- Order Status -->
                    <div>
                        <label class="block text-xs font-bold text-espresso mb-1.5">
                            حالة الطلب الأولية <span class="text-clay">*</span>
                        </label>
                        <select name="status" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                            <option value="confirmed" selected>تم التأكيد (Confirmed)</option>
                            <option value="processing">قيد التجهيز والتغليف (Processing)</option>
                            <option value="ready_for_pickup">جاهز للاستلام بالفرع (Ready for Pickup)</option>
                            <option value="on_the_way">مع المندوب / شركة الشحن (On the Way)</option>
                            <option value="delivered">تم التسليم للعميل (Delivered)</option>
                            <option value="pending">قيد الانتظار (Pending)</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-xs font-bold text-espresso mb-1.5">
                            طريقة الدفع <span class="text-clay">*</span>
                        </label>
                        <select name="payment_method" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                            <option value="cash_on_delivery" selected>💵 الدفع نقداً عند الاستلام (COD)</option>
                            <option value="instapay">📱 تحويل إنستاباي (InstaPay)</option>
                            <option value="cash_in_branch">🏪 كاش داخل المعرض</option>
                            <option value="vodafone_cash">📱 فودافون كاش / محفظة إلكترونية</option>
                        </select>
                    </div>

                    <!-- General Notes -->
                    <div>
                        <label class="block text-xs font-bold text-espresso mb-1.5">
                            ملاحظات عامة حول الطلب
                        </label>
                        <textarea name="notes" rows="3"
                                  placeholder="أي تعليمات إضافية، موعد الشحن المفضل..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Grand Total & Submit Card -->
                <div class="bg-action text-action-ink rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-sand uppercase tracking-wider">
                        ملخص الحساب الإجمالي
                    </h3>

                    <div class="space-y-2 border-b border-white/10 pb-4 text-xs">
                        <div class="flex justify-between text-linen">
                            <span>عدد القطع:</span>
                            <span class="font-bold" x-text="items.length + ' قطعة'"></span>
                        </div>
                        <div class="flex justify-between text-linen">
                            <span>إجمالي المنتجات:</span>
                            <span class="font-bold font-mono" x-text="grandTotal.toFixed(2) + ' ج.م'"></span>
                        </div>
                        <div class="flex justify-between text-linen">
                            <span>الشحن والتوصيل:</span>
                            <span class="text-sand font-bold" x-text="deliveryType === 'delivery' ? 'حسب شركة الشحن' : 'مجاناً (استلام فرع)'"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] text-sand block mb-1">المبلغ المطلوب تحصيله:</span>
                        <div class="text-2xl font-bold text-white font-mono" x-text="grandTotal.toFixed(2) + ' ج.م'"></div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <button type="submit" 
                                class="w-full py-3 px-6 rounded-xl bg-cream hover:bg-vanilla text-espresso font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>تسجيل وحفظ الطلب الآن</span>
                        </button>

                        <a href="{{ route('admin.orders.index') }}" 
                           class="w-full py-2 px-4 rounded-xl border border-white/20 hover:bg-white/10 text-linen text-xs font-semibold text-center block transition-colors">
                            إلغاء والعودة
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
                    portion_label: '',
                    quantity: 1,
                    price: 0,
                    notes: ''
                }
            ],
            addItem() {
                this.items.push({
                    product_id: '',
                    portion_label: '',
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