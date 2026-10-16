@extends('layouts.admin')

@section('title', 'تعديل بيانات الطلب ' . ($order->public_order_id ?? '#' . $order->id))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-espresso">تعديل بيانات الطلب {{ $order->public_order_id ?? '#' . $order->id }}</h1>
            <p class="text-xs text-mocha mt-1">تحديث بيانات العميل، العنوان، طريقة الاستلام والدفع</p>
        </div>
        <a href="{{ route('admin.orders.show', $order->id) }}" 
           class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
            عودة لتفاصيل الطلب
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-espresso mb-1.5">
                        اسم العميل <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           name="customer_name" 
                           value="{{ old('customer_name', $order->customer_name ?: ($order->user->name ?? '')) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-espresso mb-1.5">
                        رقم الهاتف / التواصل <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           name="phone" 
                           dir="ltr"
                           value="{{ old('phone', $order->phone) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action font-mono text-right transition-all" 
                           required>
                    @error('phone')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-espresso mb-1.5">
                        طريقة الاستلام والشحن
                    </label>
                    <select name="delivery_type" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                        <option value="delivery" {{ $order->delivery_type === 'delivery' ? 'selected' : '' }}>شحن وتوصيل للعنوان</option>
                        <option value="pickup" {{ $order->delivery_type === 'pickup' ? 'selected' : '' }}>استلام من المعرض / الفرع</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-espresso mb-1.5">
                        طريقة الدفع
                    </label>
                    <select name="payment_method" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                        <option value="cash_on_delivery" {{ in_array($order->payment_method, ['cash', 'cash_on_delivery']) ? 'selected' : '' }}>الدفع نقداً عند الاستلام</option>
                        <option value="instapay" {{ $order->payment_method === 'instapay' ? 'selected' : '' }}>تحويل إنستاباي (InstaPay)</option>
                        <option value="credit" {{ $order->payment_method === 'credit' ? 'selected' : '' }}>بطاقة بنكية / فيزا</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-espresso mb-1.5">
                    عنوان الشحن والتسليم
                </label>
                <textarea name="address" 
                          rows="2" 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">{{ old('address', $order->address) }}</textarea>
                @error('address')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-espresso mb-1.5">
                    حالة الطلب
                </label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>تم التأكيد</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                    <option value="delivered" {{ in_array($order->status, ['delivered', 'completed']) ? 'selected' : '' }}>تم التسليم بنجاح</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                @error('status')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-5 border-t border-sand/40 flex items-center justify-end gap-3">
                <a href="{{ route('admin.orders.show', $order->id) }}" 
                   class="px-5 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold shadow-sm transition-all cursor-pointer">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
