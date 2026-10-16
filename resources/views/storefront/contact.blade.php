{{-- resources/views/storefront/contact.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تواصل معنا | خدمة عملاء ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="bg-gray-50 min-h-screen py-10" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- مسار التنقل -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#AC9653] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">اتصل بنا وخدمة العملاء</span>
        </nav>

        <!-- الهيدر الرئيسي -->
        <div class="relative overflow-hidden bg-gradient-to-l from-[#AC9653] via-[#BDAB75] to-[#C2B280] text-white p-8 sm:p-12 text-center space-y-4 rounded-2xl shadow-sm border border-[#BDAB75]/40">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/20 backdrop-blur-xs rounded-full text-white text-xs font-bold mx-auto">
                <span>🇪🇬</span>
                <span>خدمة عملاء متجر {{ settings('site_name', 'أزياء لافيستا') }} للملابس</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black max-w-2xl mx-auto leading-tight">
                نسعد بتواصلك واستفسارك في أي وقت
            </h1>
            <p class="text-xs sm:text-sm text-white/95 max-w-xl mx-auto leading-relaxed">
                فريق خدمة العملاء جاهز للإجابة عن كافة استفساراتكم بشأن المقاسات، مواعيد الشحن لمحافظات مصر، واستبدال واسترجاع المنتجات.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- العمود الجانبي: بيانات التواصل المباشرة وموقع البوتيك (5 أعمدة) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-[#FDFBF7] p-8 border border-[#BDAB75]/30 space-y-6 text-right">
                    <h3 class="font-serif-luxury font-bold text-lg text-[#2B2118] border-b border-[#BDAB75]/25 pb-3">
                        قنوات الكونسيرج المباشرة
                    </h3>

                    <!-- الهاتف المباشر -->
                    <div class="space-y-1">
                        <span class="text-xs text-[#8F7B63] font-medium block">هاتف الكونسيرج والاستفسارات:</span>
                        <a href="tel:+966114008899" class="text-base font-mono font-bold text-[#AC9653] hover:underline" dir="ltr">
                            +966 11 400 8899
                        </a>
                    </div>

                    <!-- واتساب الكونسيرج الفاخر -->
                    <div class="space-y-1">
                        <span class="text-xs text-[#8F7B63] font-medium block">واتساب الكونسيرج الفوري:</span>
                        <a href="https://wa.me/966114008899" target="_blank" class="text-base font-mono font-bold text-[#2B2118] hover:text-[#AC9653] flex items-center gap-2 justify-end" dir="ltr">
                            <span>+966 11 400 8899</span>
                            <span>💬</span>
                        </a>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="space-y-1">
                        <span class="text-xs text-[#8F7B63] font-medium block">المراسلات الرسمية والخاصة:</span>
                        <span class="text-sm font-mono text-[#2B2118] font-bold block" dir="ltr">
                            concierge@sahara-atelier.com
                        </span>
                    </div>

                    <!-- موقع البوتيك -->
                    <div class="pt-4 border-t border-[#BDAB75]/25 space-y-1">
                        <span class="text-xs text-[#8F7B63] font-medium block">عنوان البوتيك وصالون العرض:</span>
                        <p class="text-xs font-bold text-[#2B2118] leading-relaxed">
                            حي العليا للأزياء الفاخرة، تقاطع طريق العروبة مع الملك فهد، الرياض، المملكة العربية السعودية.
                        </p>
                        <p class="text-[11px] text-[#8F7B63] pt-1">
                            ساعات العمل: السبت إلى الخميس (10:00 صباحاً – 10:00 مساءً)
                        </p>
                    </div>

                </div>

            </div>

            <!-- العمود الرئيسي: نموذج المراسلة الفاخر (7 أعمدة) -->
            <div class="lg:col-span-7">
                <div class="bg-white p-8 sm:p-10 border border-[#BDAB75]/35 shadow-xs text-right space-y-6">
                    <div class="space-y-1 border-b border-gray-100 pb-4">
                        <span class="text-xs font-bold tracking-wider text-[#AC9653] uppercase block">نموذج التواصل السريع</span>
                        <h3 class="font-bold text-xl text-gray-900">أرسل استفسارك لخدمة عملاء {{ settings('site_name', 'أزياء لافيستا') }}</h3>
                    </div>

                    <form onsubmit="event.preventDefault(); toastr.success('تم استلام رسالتك بنجاح، سيتواصل معك فريق خدمة العملاء قريباً.'); this.reset();" class="space-y-4">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-800 block">الاسم الكريم *</label>
                                <input type="text" required placeholder="الاسم ثلاثياً" class="w-full px-4 py-2.5 bg-gray-50 text-xs border border-gray-200 rounded-lg focus:border-[#AC9653] outline-none transition-colors" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-800 block">رقم الموبايل *</label>
                                <input type="tel" required placeholder="01XXXXXXXXX" class="w-full px-4 py-2.5 bg-gray-50 text-xs border border-gray-200 rounded-lg focus:border-[#AC9653] outline-none transition-colors font-mono text-left" dir="ltr" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-[#2B2118] block">البريد الإلكتروني *</label>
                                <input type="email" required placeholder="name@domain.com" class="w-full px-4 py-3 bg-[#FDFBF7] text-xs border border-[#BDAB75]/30 focus:border-[#AC9653] outline-none transition-colors font-mono text-left" dir="ltr" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-[#2B2118] block">نوع الاستفسار</label>
                                <select class="w-full px-4 py-3 bg-[#FDFBF7] text-xs border border-[#BDAB75]/30 focus:border-[#AC9653] outline-none transition-colors">
                                    <option>استشارة مقاسات وقصات خاصة</option>
                                    <option>حجز موعد في صالون الرياض</option>
                                    <option>استفسار عن توفر قطعة معينة</option>
                                    <option>طلب تفصيل وإصدار خاص</option>
                                    <option>خدمات الإهداء الفاخر</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-[#2B2118] block">تفاصيل الرسالة أو الطلب *</label>
                            <textarea rows="5" required placeholder="اكتب تفاصيل استفسارك أو رغبتك هنا..." class="w-full px-4 py-3 bg-[#FDFBF7] text-xs border border-[#BDAB75]/30 focus:border-[#AC9653] outline-none transition-colors leading-relaxed"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-[#CBA367] hover:bg-[#b88f55] text-white font-bold text-xs tracking-wider transition-all shadow-md active:scale-98 cursor-pointer">
                            إرسال الرسالة إلى كونسيرج الأتيليه
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
