@extends('layouts.admin')

@section('title', __('إعدادات متجر أزياء لافيستا'))

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ 
    activeTab: 'general',
    savedNotice: false 
}">
 
    <!-- Header -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary-50 text-primary-800 border border-primary-100">
                    ⚙️ لوحة التحكم والإعدادات المركزية
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500 font-medium">جاهز للتسليم للإدارة</span>
            </div>
            <h1 class="font-sans text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('إعدادات المتجر وهوية الموقع') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                {{ __('تخصيص كامل لكافة إعدادات الموقع، أرقام التواصل، طرق الدفع وإنستاباي، أسعار التوصيل، وسياسات المتجر.') }}
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('site.home') }}" target="_blank" class="px-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold transition-all flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>{{ __('معاينة المتجر المباشر') }}</span>
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-xs">
        <span class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">✓</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (isset($errors) && $errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1.5 shadow-xs">
        <div class="font-bold flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-rose-200 text-rose-800 flex items-center justify-center">!</span>
            <span>يرجى تصحيح الأخطاء التالية قبل الحفظ:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 pr-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Horizontal Tabs Navigation (Scrollable on mobile) -->
        <div class="bg-white border border-gray-100 rounded-2xl p-1.5 shadow-2xs overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max">
                
                <!-- Tab 1: General -->
                <button type="button" 
                    @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>🏪</span>
                    <span>{{ __('الهوية والشعار') }}</span>
                </button>

                <!-- Tab 2: Contact -->
                <button type="button" 
                    @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>📞</span>
                    <span>{{ __('التواصل ومواعيد العمل') }}</span>
                </button>

                <!-- Tab 3: Payments -->
                <button type="button" 
                    @click="activeTab = 'payments'"
                    :class="activeTab === 'payments' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>💳</span>
                    <span>{{ __('الدفع وإنستاباي') }}</span>
                </button>

                <!-- Tab 4: Shipping -->
                <button type="button" 
                    @click="activeTab = 'shipping'"
                    :class="activeTab === 'shipping' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>🚚</span>
                    <span>{{ __('الشحن والطلبات') }}</span>
                </button>

                <!-- Tab 5: Announcements -->
                <button type="button" 
                    @click="activeTab = 'announcements'"
                    :class="activeTab === 'announcements' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>📢</span>
                    <span>{{ __('شريط الإعلانات') }}</span>
                </button>

                <!-- Tab 6: Social -->
                <button type="button" 
                    @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>🌐</span>
                    <span>{{ __('مواقع التواصل') }}</span>
                </button>

                <!-- Tab 7: SEO -->
                <button type="button" 
                    @click="activeTab = 'seo'"
                    :class="activeTab === 'seo' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>🔍</span>
                    <span>{{ __('السيو ومحركات البحث') }}</span>
                </button>

                <!-- Tab 8: Policies -->
                <button type="button" 
                    @click="activeTab = 'policies'"
                    :class="activeTab === 'policies' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>📜</span>
                    <span>{{ __('من نحن والسياسات') }}</span>
                </button>

                <!-- Tab 9: Features -->
                <button type="button" 
                    @click="activeTab = 'features'"
                    :class="activeTab === 'features' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>⚙️</span>
                    <span>{{ __('الميزات والصيانة') }}</span>
                </button>

                <!-- Tab 10: Magazine & Offers -->
                <button type="button" 
                    @click="activeTab = 'magazine'"
                    :class="activeTab === 'magazine' ? 'bg-primary-800 text-white font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                    class="px-4 py-2.5 text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                    <span>📰</span>
                    <span>{{ __('مجلة العروض والتوقيت') }}</span>
                </button>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: General & Identity                  -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'general'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>🏪</span>
                    <span>{{ __('هوية المتجر والشعار والعملة') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تعديل الاسم المعروض في الترويسة، الشعار اللفظي، ورموز العملة.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('اسم المتجر الرسمي') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="settings[site_name]" value="{{ $settings['site_name'] ?? 'أزياء لافيستا' }}" required
                        placeholder="أزياء لافيستا"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                    <span class="text-[11px] text-gray-400 mt-1 block">يظهر في ترويسة الموقع، الفواتير، ورسائل الواتساب.</span>
                </div>

                <!-- Site Tagline -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('الشعار اللفظي / السلوجان') }}
                    </label>
                    <input type="text" name="settings[site_tagline]" value="{{ $settings['site_tagline'] ?? 'أناقة راقية، خامات قطنية مصرية فاخرة، وتصاميم استثنائية' }}"
                        placeholder="أناقة راقية، خامات قطنية مصرية فاخرة، وتصاميم استثنائية"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                    <span class="text-[11px] text-gray-400 mt-1 block">يظهر أسفل الشعار وفي بطاقات المشاركة والترويسة.</span>
                </div>

                <!-- Currency Symbol -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رمز العملة المعروض للعملاء') }}
                    </label>
                    <input type="text" name="settings[currency_symbol]" value="{{ $settings['currency_symbol'] ?? 'ج.م' }}"
                        placeholder="ج.م"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Currency Code -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('كود العملة الأساسي (ISO)') }}
                    </label>
                    <input type="text" name="settings[default_currency]" value="{{ $settings['default_currency'] ?? 'EGP' }}" dir="ltr"
                        placeholder="EGP"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Logos & Images Grid -->
            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Site Logo -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-800">
                            {{ __('شعار المتجر (Site Logo)') }}
                        </label>
                        <span class="text-[10px] text-gray-400">PNG, JPG, WEBP, SVG</span>
                    </div>

                    @if(get_site_logo())
                    <div class="p-3 bg-white rounded-xl border border-gray-200 space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-20 h-14 bg-gray-50 rounded-lg p-1.5 flex items-center justify-center border border-gray-100 shrink-0">
                                <img src="{{ get_site_logo() }}" alt="Logo" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="text-[11px] text-gray-500">
                                <span class="font-bold text-gray-700 block">الشعار مفعل حالياً</span>
                                <span>يتم تطبيقه تلقائياً في الترويسة، القائمة الجانبية، والفوتر.</span>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 pt-2 border-t border-gray-100 text-xs text-rose-600 cursor-pointer font-bold select-none">
                            <input type="checkbox" name="remove_site_logo" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            <span>حذف الشعار المرفوع والعودة للشعار الذهبي الافتراضي</span>
                        </label>
                    </div>
                    @else
                    <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 text-[11px] text-amber-800 flex items-center gap-2">
                        <span class="font-bold text-sm text-[#AC9653]">ت</span>
                        <span>يتم حالياً استخدام الشعار النصي الذهبي الفاخر بناءً على اسم المتجر. يمكنك رفع شعار مصور مخصص في أي وقت.</span>
                    </div>
                    @endif

                    <input type="file" name="site_logo" accept="image/*" 
                        class="w-full text-xs text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-primary-800 file:cursor-pointer">
                    <p class="text-[10px] text-gray-400">موصى به: خلفية شفافة بأبعاد تقريبية 240x80 بكسل.</p>
                </div>

                <!-- Site Favicon -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-800">
                            {{ __('أيقونة التاب في المتصفح (Favicon)') }}
                        </label>
                        <span class="text-[10px] text-gray-400">ICO, PNG, SVG</span>
                    </div>

                    @if(get_site_favicon())
                    <div class="p-3 bg-white rounded-xl border border-gray-200 space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg p-1 flex items-center justify-center border border-gray-100 shrink-0">
                                <img src="{{ get_site_favicon() }}" alt="Favicon" class="w-8 h-8 object-contain">
                            </div>
                            <div class="text-[11px] text-gray-500">
                                <span class="font-bold text-gray-700 block">أيقونة التاب الحالية</span>
                                <span>تظهر بجوار عنوان الموقع في متصفح الزائر.</span>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 pt-2 border-t border-gray-100 text-xs text-rose-600 cursor-pointer font-bold select-none">
                            <input type="checkbox" name="remove_site_favicon" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            <span>حذف أيقونة التاب المخصصة</span>
                        </label>
                    </div>
                    @endif

                    <input type="file" name="site_favicon" accept="image/*,.ico" 
                        class="w-full text-xs text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-primary-800 file:cursor-pointer">
                    <p class="text-[10px] text-gray-400">موصى به: أيقونة مربعة بأبعاد 32x32 أو 64x64 بكسل بصيغة PNG أو ICO.</p>
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: Contact & Working Hours             -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'contact'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>📞</span>
                    <span>{{ __('بيانات التواصل، فروع العامرية، ومواعيد العمل') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تعديل أرقام الهواتف، الواتساب الموحد، البريد، وعناوين الفروع.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Store Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رقم هاتف المتجر والشكاوى الرئيسي') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="settings[store_phone]" value="{{ $settings['store_phone'] ?? '01000000000' }}" dir="ltr" required
                        placeholder="01000000000"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                    <span class="text-[11px] text-gray-400 mt-1 block">يظهر في التوب بار والفوتر لزر الاتصال السريع.</span>
                </div>

                <!-- Secondary Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رقم هاتف إضافي / خط أرضي') }}
                    </label>
                    <input type="text" name="settings[store_phone_secondary]" value="{{ $settings['store_phone_secondary'] ?? '034400000' }}" dir="ltr"
                        placeholder="034400000"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- WhatsApp Official -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رقم الواتساب الرسمي الموحد للطلبات') }} <span class="text-emerald-600 font-normal">(مع كود الدولة مثل 201...)</span>
                    </label>
                    <input type="text" name="settings[store_whatsapp]" value="{{ $settings['store_whatsapp'] ?? '201000000000' }}" dir="ltr" required
                        placeholder="201000000000"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-emerald-50/50 border border-emerald-300 text-emerald-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition-all">
                    <span class="text-[11px] text-gray-400 mt-1 block">يرسل إليه العملاء طلباتهم الفورية وزر الواتساب العائم.</span>
                </div>

                <!-- Store Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('البريد الإلكتروني الرسمي للمتجر') }}
                    </label>
                    <input type="email" name="settings[store_email]" value="{{ $settings['store_email'] ?? 'info@altawheed-dairy.com' }}" dir="ltr"
                        placeholder="info@altawheed-dairy.com"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Working Hours -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('مواعيد العمل الرسمية واستقبال الزوار') }}
                    </label>
                    <input type="text" name="settings[working_hours]" value="{{ $settings['working_hours'] ?? 'يومياً: من 8:00 صباحاً حتى 2:00 بعد منتصف الليل' }}"
                        placeholder="يومياً: من 8:00 صباحاً حتى 2:00 بعد منتصف الليل"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Delivery Working Hours -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('مواعيد حركة أسطول التوصيل للمنازل') }}
                    </label>
                    <input type="text" name="settings[delivery_working_hours]" value="{{ $settings['delivery_working_hours'] ?? 'خدمة التوصيل السريع متاحة يومياً من 8:00 ص حتى 2:00 ص' }}"
                        placeholder="خدمة التوصيل السريع متاحة يومياً من 8:00 ص حتى 2:00 ص"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('عنوان المقر الرئيسي / فرع الإدارة') }}
                    </label>
                    <input type="text" name="settings[address]" value="{{ $settings['address'] ?? 'شارع المستشفى - بجوار الإسعاف، العامرية، الإسكندرية' }}"
                        placeholder="شارع المستشفى - بجوار الإسعاف، العامرية، الإسكندرية"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Google Maps URL -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رابط موقع الفرع الرئيسي على خرائط جوجل (Google Maps Link)') }}
                    </label>
                    <input type="url" name="settings[google_maps_url]" value="{{ $settings['google_maps_url'] ?? '' }}" dir="ltr"
                        placeholder="https://maps.google.com/?q=..."
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: Payments & InstaPay                 -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'payments'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>💳</span>
                    <span>{{ __('إعدادات الدفع، إنستاباي، ومحافظ الكاش') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تحديد طرق الدفع المتاحة في صفحة إتمام الطلب (Checkout) وبيانات الحسابات البنكية والمحافظ.</p>
            </div>

            <!-- Toggles for Payment Methods -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- COD Toggle -->
                <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-900 block">الدفع عند الاستلام (كاش)</span>
                        <span class="text-[10px] text-gray-500">نقداً للمندوب أو بالفرع</span>
                    </div>
                    <select name="settings[enable_cod]" class="text-xs font-bold rounded-xl border-gray-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-primary-700">
                        <option value="1" {{ ($settings['enable_cod'] ?? '1') == '1' ? 'selected' : '' }}>مفعل ✓</option>
                        <option value="0" {{ ($settings['enable_cod'] ?? '1') == '0' ? 'selected' : '' }}>معطل ✗</option>
                    </select>
                </div>

                <!-- InstaPay Toggle -->
                <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/40 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-rose-900 block">الدفع عبر InstaPay</span>
                        <span class="text-[10px] text-rose-600">تحويل فوري + رفع إيصال</span>
                    </div>
                    <select name="settings[enable_instapay]" class="text-xs font-bold rounded-xl border-rose-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-rose-600 text-rose-800">
                        <option value="1" {{ ($settings['enable_instapay'] ?? '1') == '1' ? 'selected' : '' }}>مفعل ✓</option>
                        <option value="0" {{ ($settings['enable_instapay'] ?? '1') == '0' ? 'selected' : '' }}>معطل ✗</option>
                    </select>
                </div>

                <!-- Vodafone Cash Toggle -->
                <div class="p-4 rounded-2xl border border-red-200 bg-red-50/40 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-red-900 block">فودافون كاش / محافظ</span>
                        <span class="text-[10px] text-red-600">محافظ الهاتف الذكية</span>
                    </div>
                    <select name="settings[enable_vodafone_cash]" class="text-xs font-bold rounded-xl border-red-300 bg-white px-3 py-1.5 focus:ring-2 focus:ring-red-600 text-red-800">
                        <option value="1" {{ ($settings['enable_vodafone_cash'] ?? '1') == '1' ? 'selected' : '' }}>مفعل ✓</option>
                        <option value="0" {{ ($settings['enable_vodafone_cash'] ?? '1') == '0' ? 'selected' : '' }}>معطل ✗</option>
                    </select>
                </div>

            </div>

            <!-- InstaPay Account Details Box -->
            <div class="p-6 rounded-3xl bg-rose-50/60 border border-rose-200 space-y-4">
                <div class="flex items-center gap-2 text-rose-900 font-bold text-xs pb-2 border-b border-rose-200/60">
                    <span class="w-6 h-6 rounded-full bg-rose-200 text-rose-800 flex items-center justify-center font-bold">⚡</span>
                    <span>بيانات حساب إنستاباي المعروضة للعميل للتحويل المباشر</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('عنوان الدفع اللحظي (IPA / Username)') }}
                        </label>
                        <input type="text" name="settings[instapay_username]" value="{{ $settings['instapay_username'] ?? 'altawheed@instapay' }}" dir="ltr"
                            placeholder="altawheed@instapay"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-rose-300 text-rose-900 font-mono font-bold focus:ring-2 focus:ring-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('رقم هاتف حساب إنستاباي') }}
                        </label>
                        <input type="text" name="settings[instapay_number]" value="{{ $settings['instapay_number'] ?? '01000000000' }}" dir="ltr"
                            placeholder="01000000000"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-rose-300 text-rose-900 font-mono font-bold focus:ring-2 focus:ring-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('اسم صاحب الحساب في تطبيق إنستاباي') }}
                        </label>
                        <input type="text" name="settings[instapay_name]" value="{{ $settings['instapay_name'] ?? 'أزياء لافيستا' }}"
                            placeholder="أزياء لافيستا"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-rose-300 text-gray-900 font-bold focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1.5">
                        {{ __('تعليمات الدفع عبر إنستاباي للعميل') }}
                    </label>
                    <textarea name="settings[instapay_instructions]" rows="2"
                        class="w-full p-3 text-xs rounded-xl bg-white border border-rose-300 text-gray-900 focus:ring-2 focus:ring-rose-500 leading-relaxed">{{ $settings['instapay_instructions'] ?? 'يرجى تحويل القيمة عبر تطبيق InstaPay ثم إرفاق صورة إشعار التحويل لتأكيد تجهيز الطلب فوراً.' }}</textarea>
                </div>
            </div>

            <!-- Vodafone Cash Number -->
            <div class="p-6 rounded-3xl bg-red-50/50 border border-red-200 space-y-4">
                <div class="flex items-center gap-2 text-red-900 font-bold text-xs">
                    <span>📱</span>
                    <span>بيانات محفظة فودافون كاش / المحافظ الإلكترونية</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('رقم محفظة فودافون كاش الرسمية') }}
                        </label>
                        <input type="text" name="settings[vodafone_cash_number]" value="{{ $settings['vodafone_cash_number'] ?? '01000000000' }}" dir="ltr"
                            placeholder="01000000000"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-red-300 text-red-900 font-mono font-bold focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 4: Shipping & Order Limits             -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'shipping'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>🚚</span>
                    <span>{{ __('إعدادات الشحن، التوصيل بالعامرية، والحدود المالية') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تحديد رسوم التوصيل، قيمة التوصيل المجاني، والحد الأدنى لإنشاء الطلب.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Delivery Fee -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">
                        {{ __('رسوم التوصيل الافتراضية بالعامرية (ج.م)') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" name="settings[delivery_fee]" value="{{ $settings['delivery_fee'] ?? '20' }}" required
                        class="w-full px-4 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-mono font-bold focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 block">تضاف تلقائياً للطلبات التي تختار التوصيل للمنزل.</span>
                </div>

                <!-- Free Delivery Threshold -->
                <div class="p-5 rounded-2xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                    <label class="block text-xs font-bold text-emerald-950">
                        {{ __('الحد الأدنى للشحن المجاني (ج.م)') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="1" name="settings[free_delivery_threshold]" value="{{ $settings['free_delivery_threshold'] ?? '300' }}" required
                        class="w-full px-4 py-2.5 text-xs rounded-xl bg-white border border-emerald-300 text-emerald-900 font-mono font-bold focus:ring-2 focus:ring-emerald-600">
                    <span class="text-[10px] text-emerald-700 block">يصبح التوصيل 0 ج.م تلقائياً عند وصول الطلب لهذه القيمة.</span>
                </div>

                <!-- Min Order Amount -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">
                        {{ __('الحد الأدنى لقيمة الطلب (ج.م)') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="1" name="settings[min_order_amount]" value="{{ $settings['min_order_amount'] ?? '50' }}" required
                        class="w-full px-4 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-mono font-bold focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 block">أقل مبلغ يسمح النظام بإنشاء الطلب به في السلة.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <!-- Estimated Delivery Time -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('متوسط وقت التوصيل المتوقع') }}
                    </label>
                    <input type="text" name="settings[estimated_delivery_time]" value="{{ $settings['estimated_delivery_time'] ?? '30 - 45 دقيقة' }}"
                        placeholder="30 - 45 دقيقة"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>

                <!-- Allow Branch Pickup Option -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('إتاحة خيار الاستلام من الفرع مجاناً (Pickup)') }}
                    </label>
                    <select name="settings[allow_store_pickup]" class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                        <option value="1" {{ ($settings['allow_store_pickup'] ?? '1') == '1' ? 'selected' : '' }}>متاح (يمكن للعميل اختيار استلام من أحد الفروع) ✓</option>
                        <option value="0" {{ ($settings['allow_store_pickup'] ?? '1') == '0' ? 'selected' : '' }}>معطل (توصيل للمنازل فقط) ✗</option>
                    </select>
                </div>

                <!-- Coverage Areas Note -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('مناطق التغطية بالتوصيل الفوري بالعامرية') }}
                    </label>
                    <input type="text" name="settings[delivery_coverage_areas]" value="{{ $settings['delivery_coverage_areas'] ?? 'العامرية، كينج مريوط، شارع الإسعاف، النهضة، عبد القادر، وقرى مريوط' }}"
                        placeholder="العامرية، كينج مريوط، شارع الإسعاف، النهضة، عبد القادر، وقرى مريوط"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: Announcement & Top Utility Bar      -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'announcements'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>{{ __('شريط الإعلانات والخدمات العلوي (Top Announcement Strip)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">التحكم الكامل في شريط الترويسة العلوي الملون (أرقام خدمة العملاء، نص الضمان والمعاينة، طرق الدفع).</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('حالة تفعيل الشريط العلوي بالكامل') }}
                    </label>
                    <select name="settings[topbar_enabled]" class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                        <option value="1" {{ ($settings['topbar_enabled'] ?? '1') == '1' ? 'selected' : '' }}>مفعل وظاهر في جميع الصفحات ✓</option>
                        <option value="0" {{ ($settings['topbar_enabled'] ?? '1') == '0' ? 'selected' : '' }}>مخفي / معطل ✗</option>
                    </select>
                </div>

                <!-- Topbar Direct Customer Care Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('هاتف خدمة العملاء بالشريط العلوي') }}
                    </label>
                    <input type="text" name="settings[topbar_phone]" value="{{ $settings['topbar_phone'] ?? ($settings['store_phone'] ?? '01000000000') }}" dir="ltr"
                        placeholder="01000000000"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 mt-1 block">يظهر بجوار أيقونة الاتصال المباشر لخدمة العملاء.</span>
                </div>

                <!-- Announcement Link -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('رابط يفتح عند النقر على إعلان الشريط (اختياري)') }}
                    </label>
                    <input type="text" name="settings[announcement_bar_link]" value="{{ $settings['announcement_bar_link'] ?? '' }}" dir="ltr"
                        placeholder="https://... أو /shop"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Payment Note Strip -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص طرق الدفع المعتمدة بجوار الهاتف') }}
                    </label>
                    <input type="text" name="settings[topbar_payment_note]" value="{{ $settings['topbar_payment_note'] ?? 'دفع عند الاستلام • فودافون كاش • إنستاباي' }}"
                        placeholder="دفع عند الاستلام • فودافون كاش • إنستاباي"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 mt-1 block">يظهر بجوار رقم الهاتف لطمأنة العميل بشأن خيارات الدفع المتاحة.</span>
                </div>

                <!-- Main Desktop Guarantee & Announcement Text -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص إعلان الضمان والشحن في منتصف الشريط (للشاشات والكمبيوتر)') }}
                    </label>
                    <input type="text" name="settings[topbar_announcement]" value="{{ $settings['topbar_announcement'] ?? ($settings['announcement_bar_text'] ?? 'شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً') }}"
                        placeholder="شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 mt-1 block">الرسالة الترويجية المركزية مع وميض النجمة.</span>
                </div>

                <!-- Mobile Short Announcement Text -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص إعلان الشريط المختصر لشاشات الموبايل') }}
                    </label>
                    <input type="text" name="settings[topbar_announcement_mobile]" value="{{ $settings['topbar_announcement_mobile'] ?? 'معاينة وقياس قبل الدفع • شحن لكافة المحافظات' }}"
                        placeholder="معاينة وقياس قبل الدفع • شحن لكافة المحافظات"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                    <span class="text-[10px] text-gray-400 mt-1 block">نص مقتضب ومناسب لعرض شاشات الهواتف دون حدوث كسر بالأسطر.</span>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 6: Social Media & Channels             -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'social'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span>{{ __('روابط شبكات التواصل الاجتماعي الرسمية وقنوات المتابعة') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تظهر تلقائياً في فوتر المتجر وصفحات التواصل لزيادة متابعين البراند وتفاعل العملاء.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-[#1877F2] text-white flex items-center justify-center text-[11px] font-black">f</span>
                        <span>{{ __('رابط صفحة فيسبوك') }}</span>
                    </label>
                    <input type="url" name="settings[facebook_link]" value="{{ $settings['facebook_link'] ?? '' }}" dir="ltr"
                        placeholder="https://facebook.com/altawhid.fashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-gradient-to-tr from-[#F58529] via-[#DD2A7B] to-[#8134AF] text-white flex items-center justify-center text-[10px]">📷</span>
                        <span>{{ __('رابط حساب إنستغرام') }}</span>
                    </label>
                    <input type="url" name="settings[instagram_link]" value="{{ $settings['instagram_link'] ?? '' }}" dir="ltr"
                        placeholder="https://instagram.com/altawhid.fashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- TikTok -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-black text-white flex items-center justify-center text-[10px]">🎵</span>
                        <span>{{ __('رابط حساب تيك توك') }}</span>
                    </label>
                    <input type="url" name="settings[tiktok_link]" value="{{ $settings['tiktok_link'] ?? '' }}" dir="ltr"
                        placeholder="https://tiktok.com/@altawhid.fashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- YouTube -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-[#FF0000] text-white flex items-center justify-center text-[10px]">▶</span>
                        <span>{{ __('رابط قناة يوتيوب') }}</span>
                    </label>
                    <input type="url" name="settings[youtube_link]" value="{{ $settings['youtube_link'] ?? '' }}" dir="ltr"
                        placeholder="https://youtube.com/@altawhidfashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Twitter / X -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-black text-white flex items-center justify-center text-[11px] font-mono font-bold">𝕏</span>
                        <span>{{ __('رابط حساب منصة X (تويتر سابقاً)') }}</span>
                    </label>
                    <input type="url" name="settings[twitter_link]" value="{{ $settings['twitter_link'] ?? '' }}" dir="ltr"
                        placeholder="https://x.com/altawhidfashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Telegram -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-[#229ED9] text-white flex items-center justify-center text-[10px]">✈</span>
                        <span>{{ __('رابط قناة تيليجرام للعروض الحصرية') }}</span>
                    </label>
                    <input type="url" name="settings[telegram_link]" value="{{ $settings['telegram_link'] ?? '' }}" dir="ltr"
                        placeholder="https://t.me/altawhidfashion"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Snapchat -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-[#FFFC00] text-black flex items-center justify-center text-[10px]">👻</span>
                        <span>{{ __('رابط حساب سناب شات') }}</span>
                    </label>
                    <input type="url" name="settings[snapchat_link]" value="{{ $settings['snapchat_link'] ?? '' }}" dir="ltr"
                        placeholder="https://snapchat.com/add/altawhid"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- WhatsApp Direct Chat Link -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-[#25D366] text-white flex items-center justify-center text-[10px]">💬</span>
                        <span>{{ __('رابط محادثة واتساب المباشرة أو قروب VIP') }}</span>
                    </label>
                    <input type="url" name="settings[whatsapp_group_link]" value="{{ $settings['whatsapp_group_link'] ?? '' }}" dir="ltr"
                        placeholder="https://chat.whatsapp.com/... أو https://wa.me/201..."
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 7: SEO & Analytics                     -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'seo'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>🔍</span>
                    <span>{{ __('تحسين محركات البحث (SEO) والتحليلات التسويقية') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تخصيص ظهور الموقع في نتائج بحث جوجل وبطاقات منصات التواصل (Open Graph) وأكواد التتبع.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meta Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('عنوان المتجر الافتراضي في جوجل (Meta Title)') }}
                    </label>
                    <input type="text" name="settings[meta_title]" value="{{ $settings['meta_title'] ?? 'أزياء لافيستا | أرقى الملابس والتنسيقات' }}"
                        placeholder="أزياء لافيستا | أرقى الملابس والتنسيقات"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Meta Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('الوصف الافتراضي للموقع في نتائج البحث (Meta Description)') }}
                    </label>
                    <textarea name="settings[meta_description]" rows="3"
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['meta_description'] ?? 'أزياء لافيستا. تشكيلات حصرية من أرقى الملابس والموديلات النسائية والرجالية والأطفال، خامات طبيعية وتوصيل مع حق المعاينة قبل الاستلام.' }}</textarea>
                </div>

                <!-- Meta Keywords -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('الكلمات المفتاحية (Meta Keywords - مفصولة بفواصل)') }}
                    </label>
                    <input type="text" name="settings[meta_keywords]" value="{{ $settings['meta_keywords'] ?? 'أزياء لافيستا, بوتيك لافيستا, ملابس راقية, فساتين, بدلات, أزياء كاجوال, مقاسات خاصة, توصيل ملابس' }}"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Social Share Image (OG Image) -->
                <div class="md:col-span-2 p-5 rounded-2xl border border-gray-200 bg-gray-50/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-800">
                            {{ __('صورة مشاركة الروابط في فيسبوك وواتساب (OG Share Image)') }}
                        </label>
                        <span class="text-[10px] text-gray-400">1200x630px موصى به</span>
                    </div>

                    @if(get_og_image())
                    <div class="flex items-center gap-4 p-3 bg-white rounded-xl border border-gray-200">
                        <div class="w-24 h-14 bg-gray-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                            <img src="{{ get_og_image() }}" alt="OG Cover" class="max-h-full max-w-full object-cover rounded">
                        </div>
                        <div class="text-[11px] text-gray-500">
                            <span class="font-bold text-gray-700 block">صورة بطاقة المشاركة الحالية</span>
                            <span>تظهر تلقائياً عند إرسال رابط الموقع في واتساب أو فيسبوك.</span>
                        </div>
                    </div>
                    @endif

                    <input type="file" name="og_image" accept="image/*" 
                        class="w-full text-xs text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-primary-800 file:cursor-pointer">
                </div>

                <!-- Google Analytics -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('معرف إحصائيات جوجل (Google Analytics ID)') }}
                    </label>
                    <input type="text" name="settings[google_analytics_id]" value="{{ $settings['google_analytics_id'] ?? '' }}" dir="ltr"
                        placeholder="G-XXXXXXXXXX"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>

                <!-- Facebook Pixel -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('معرف فيسبوك بيكسل (Facebook Pixel ID)') }}
                    </label>
                    <input type="text" name="settings[facebook_pixel_id]" value="{{ $settings['facebook_pixel_id'] ?? '' }}" dir="ltr"
                        placeholder="123456789012345"
                        class="w-full px-4 py-3 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono focus:ring-2 focus:ring-primary-700">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 8: About Us & Policies                 -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'policies'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>📜</span>
                    <span>{{ __('محتوى صفحة من نحن والوثائق والسياسات الرسمية') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">تخصيص قصة المتجر، نبذة الفوتر، ونصوص الشروط والخصوصية والاسترجاع.</p>
            </div>

            <div class="space-y-6">
                <!-- About Us Full -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('قصة ورؤية أزياء لافيستا (صفحة من نحن)') }}
                    </label>
                    <textarea name="settings[about_us_content]" rows="4"
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['about_us_content'] ?? 'أزياء لافيستا، وجهتك الأولى للأناقة الراقية والموديلات العصرية المختارة بعناية من أجود خامات القطن والكتان والصوف الطبيعي لتمنحك إطلالة فريدة ومقاساً مثالياً.' }}</textarea>
                </div>

                <!-- About Us Short (Footer) -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نبذة مختصرة لفوتر الموقع الرئيسي') }}
                    </label>
                    <textarea name="settings[about_us_short]" rows="2"
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['about_us_short'] ?? 'بيت الأزياء والموضة الراقية. نوفر تشكيلات متنوعة من أرقى الموديلات، الأطقم الكاملة، والأقمشة الطبيعية مع خدمة معاينة المقاس قبل الاستلام.' }}</textarea>
                </div>

                <!-- Terms Custom -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص إضافي / مخصص لصفحة الشروط والأحكام') }} <span class="text-gray-400 font-normal">(اتركه فارغاً للاعتماد على النص القياسي)</span>
                    </label>
                    <textarea name="settings[terms_content]" rows="3"
                        placeholder="أي بنود أو شروط خاصة ترغب في إضافتها للصفحة..."
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['terms_content'] ?? '' }}</textarea>
                </div>

                <!-- Privacy Custom -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص إضافي / مخصص لصفحة سياسة الخصوصية') }} <span class="text-gray-400 font-normal">(اتركه فارغاً للاعتماد على النص القياسي)</span>
                    </label>
                    <textarea name="settings[privacy_content]" rows="3"
                        placeholder="أي بنود خصوصية إضافية..."
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['privacy_content'] ?? '' }}</textarea>
                </div>

                <!-- Return Policy Custom -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        {{ __('نص إضافي / مخصص لسياسة الاستبدال والاسترجاع للملابس والأزياء') }}
                    </label>
                    <textarea name="settings[return_policy_content]" rows="3"
                        placeholder="شروط استبدال مقاسات الملابس والأزياء..."
                        class="w-full p-4 text-xs rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary-700 leading-relaxed">{{ $settings['return_policy_content'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 9: Features & Maintenance Mode         -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'features'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>⚙️</span>
                    <span>{{ __('ميزات المتجر، زر الواتساب العائم، ووضع الصيانة') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">التحكم في تشغيل وتعطيل ميزات الموقع التفاعلية ورسالة الصيانة.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Reviews Toggle -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">
                        {{ __('تقييمات وآراء العملاء على المنتجات') }}
                    </label>
                    <select name="settings[reviews_enabled]" class="w-full px-3.5 py-2 text-xs rounded-xl bg-white border border-gray-300 font-bold text-gray-900 focus:ring-2 focus:ring-primary-700">
                        <option value="1" {{ ($settings['reviews_enabled'] ?? '1') == '1' ? 'selected' : '' }}>مفعلة وتظهر بالمنتجات ✓</option>
                        <option value="0" {{ ($settings['reviews_enabled'] ?? '1') == '0' ? 'selected' : '' }}>معطلة ✗</option>
                    </select>
                </div>

                <!-- Wishlist Toggle -->
                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">
                        {{ __('ميزة قائمة المفضلة (Wishlist)') }}
                    </label>
                    <select name="settings[wishlist_enabled]" class="w-full px-3.5 py-2 text-xs rounded-xl bg-white border border-gray-300 font-bold text-gray-900 focus:ring-2 focus:ring-primary-700">
                        <option value="1" {{ ($settings['wishlist_enabled'] ?? '1') == '1' ? 'selected' : '' }}>مفعلة ✓</option>
                        <option value="0" {{ ($settings['wishlist_enabled'] ?? '1') == '0' ? 'selected' : '' }}>معطلة ✗</option>
                    </select>
                </div>

                <!-- Floating WhatsApp Toggle -->
                <div class="p-5 rounded-2xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                    <label class="block text-xs font-bold text-emerald-950">
                        {{ __('زر الواتساب العائم في أسفل الشاشة') }}
                    </label>
                    <select name="settings[whatsapp_floating_button]" class="w-full px-3.5 py-2 text-xs rounded-xl bg-white border border-emerald-300 font-bold text-emerald-900 focus:ring-2 focus:ring-emerald-600">
                        <option value="1" {{ ($settings['whatsapp_floating_button'] ?? '1') == '1' ? 'selected' : '' }}>مفعل وظاهر في كل الصفحات ✓</option>
                        <option value="0" {{ ($settings['whatsapp_floating_button'] ?? '1') == '0' ? 'selected' : '' }}>معطل ✗</option>
                    </select>
                </div>
            </div>

            <!-- Maintenance Mode Box -->
            <div class="p-6 rounded-3xl bg-amber-50/50 border border-amber-200 space-y-4">
                <div class="flex items-center gap-2 text-amber-950 font-bold text-xs pb-2 border-b border-amber-200/60">
                    <span>🚧</span>
                    <span>وضع الصيانة وإغلاق المتجر المؤقت (Maintenance Mode)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('تفعيل وضع الصيانة') }}
                        </label>
                        <select name="settings[maintenance_mode]" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-amber-300 font-bold text-gray-900 focus:ring-2 focus:ring-amber-500">
                            <option value="0" {{ ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>الموقع يعمل بشكل طبيعي ✓</option>
                            <option value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>إغلاق المتجر وعرض رسالة الصيانة ⚠️</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('رسالة وضع الصيانة المعروضة للزوار') }}
                        </label>
                        <input type="text" name="settings[maintenance_message]" value="{{ $settings['maintenance_message'] ?? 'نعتذر منكم، الموقع يخضع لصيانة وتحديثات دورية وسيعود للعمل خلال دقائق.' }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-amber-300 text-gray-900 font-medium focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 10: Magazine & Offers                  -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'magazine'" x-cloak class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6"
             x-data="{ timingMode: '{{ $settings['magazine_timing_mode'] ?? 'auto_weekly' }}' }">
            
            <div class="pb-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                        <span>📰</span>
                        <span>{{ __('إعدادات وتوقيت مجلة العروض الأسبوعية (Offers Magazine)') }}</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">التحكم الكامل في بانر المجلة العلوي، نصوص الإصدار، ونظام العداد التنازلي التلقائي أو المخصص.</p>
                </div>

                <a href="{{ route('offers.index') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 text-xs font-bold transition-all shrink-0">
                    <span>👁️</span>
                    <span>{{ __('معاينة صفحة المجلة المباشرة') }}</span>
                </a>
            </div>

            <!-- Notification & Info Box -->
            <div class="p-4.5 rounded-2xl bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 text-xs text-orange-900 space-y-2">
                <div class="font-black flex items-center gap-2 text-orange-800">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-ping"></span>
                    <span>نظام توقيت المجلة الذكي</span>
                </div>
                <p class="leading-relaxed">
                    يمكنك اختيار <strong>"أسبوعي تلقائي ذكي"</strong> لتتجدد المجلة والعداد التنازلي أوتوماتيكياً كل أسبوع حتى ليلة الأحد بدون تدخل يدوي، أو اختيار <strong>"تاريخ مخصص محدد"</strong> لمواسم خاصة مثل (عروض رمضان، عروض العيد، أو عروض نهاية الشهر) وسيعمل العداد تنازلياً حتى التاريخ الذي تحدده بالدقيقة.
                </p>
            </div>

            <!-- Section 1: Timing Mode & Countdown -->
            <div class="p-6 rounded-3xl bg-gray-50/70 border border-gray-200/80 space-y-5">
                <h3 class="text-xs font-black text-gray-900 flex items-center gap-2">
                    <span>⏱️</span>
                    <span>{{ __('نظام التوقيت والعداد التنازلي (Countdown Timer)') }}</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Timing Mode Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('وضع توقيت المجلة والعداد') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="settings[magazine_timing_mode]" x-model="timingMode"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 font-bold text-gray-900 focus:ring-2 focus:ring-primary-700 focus:border-primary-700">
                            <option value="auto_weekly">{{ __('أسبوعي تلقائي ذكي (يتجدد أسبوعياً كل يوم أحد تلقائياً)') }}</option>
                            <option value="custom">{{ __('تاريخ مخصص محدد (تحديد تاريخ بداية ونهاية يدوي للمجلة)') }}</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">الوضع التلقائي يضمن بقاء المجلة محدثة دائماً دون الحاجة لتعديلها كل أسبوع.</p>
                    </div>

                    <!-- Countdown Toggle -->
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('إظهار صندوق العداد التنازلي في المجلة') }}
                        </label>
                        <select name="settings[magazine_countdown_enabled]"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 font-bold text-gray-900 focus:ring-2 focus:ring-primary-700">
                            <option value="1" {{ ($settings['magazine_countdown_enabled'] ?? '1') == '1' ? 'selected' : '' }}>مفعل وظاهر للزوار (يوصى به لزيادة المبيعات) ✓</option>
                            <option value="0" {{ ($settings['magazine_countdown_enabled'] ?? '1') == '0' ? 'selected' : '' }}>إخفاء العداد التنازلي ✗</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Dates Fields (Shown only when timingMode === 'custom') -->
                <div x-show="timingMode === 'custom'" x-cloak class="pt-4 border-t border-gray-200/80 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-5 rounded-2xl border border-amber-200">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('تاريخ بدء المجلة المخصصة') }}
                        </label>
                        <input type="date" name="settings[magazine_start_date]" value="{{ $settings['magazine_start_date'] ?? '' }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                        <p class="text-[11px] text-gray-500 mt-1">مثال: تاريخ بداية عروض الأسبوع أو بداية الشهر.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-red-700 mb-1.5">
                            {{ __('تاريخ ووقت انتهاء المجلة والعداد التنازلي') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="settings[magazine_end_date]" value="{{ $settings['magazine_end_date'] ?? '' }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-red-300 text-gray-900 font-bold focus:ring-2 focus:ring-red-500">
                        <p class="text-[11px] text-red-600 font-medium mt-1">العداد التنازلي سيعد تنازلياً حتى هذا التاريخ والساعة بدقة.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Magazine Header Texts & Customization -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-gray-900 flex items-center gap-2">
                    <span>✍️</span>
                    <span>{{ __('نصوص وعناوين البانر الرئيسي للمجلة') }}</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Magazine Title -->
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('العنوان الرئيسي للمجلة') }}
                        </label>
                        <input type="text" name="settings[magazine_title]" value="{{ $settings['magazine_title'] ?? 'كتالوج ومجلة أزياء لافيستا' }}"
                            placeholder="كتالوج ومجلة أزياء لافيستا"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                    </div>

                    <!-- Magazine Badge -->
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('نص الشارة العلوية (Badge)') }}
                        </label>
                        <input type="text" name="settings[magazine_badge]" value="{{ $settings['magazine_badge'] ?? 'إصدار الموسم الجديد • تشكيلات حصرية' }}"
                            placeholder="إصدار الموسم الجديد • تشكيلات حصرية"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-bold focus:ring-2 focus:ring-primary-700">
                    </div>

                    <!-- Magazine Subtitle -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('الوصف الترويجي أسفل العنوان') }}
                        </label>
                        <input type="text" name="settings[magazine_subtitle]" value="{{ $settings['magazine_subtitle'] ?? 'تصفح أحدث موديلات وتنسيقات الموسم، إطلالات كاملة، وخصومات استثنائية مع حق المعاينة وقياس المقاس.' }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-medium focus:ring-2 focus:ring-primary-700">
                    </div>

                    <!-- Branch Availability Note -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('ملاحظة توافر العروض بالفروع والتوصيل') }}
                        </label>
                        <input type="text" name="settings[magazine_branch_note]" value="{{ $settings['magazine_branch_note'] ?? 'متاحة بجميع الفروع والتوصيل المنزلي' }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-gray-300 text-gray-900 font-medium focus:ring-2 focus:ring-primary-700">
                    </div>
                </div>
            </div>

            <!-- Quick Links Box -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 flex flex-wrap items-center justify-between gap-3 text-xs">
                <span class="text-gray-600 font-bold">💡 لإدارة وتعديل المنتجات داخل المجلة:</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.deals.index') }}" class="px-3 py-1.5 rounded-xl bg-white border border-gray-300 hover:border-red-500 font-bold text-gray-700 hover:text-red-600 transition-all shadow-2xs">
                        ⚡ إدارة الصفقات الخاطفة (Flash Deals)
                    </a>
                    <a href="{{ route('admin.bundles.index') }}" class="px-3 py-1.5 rounded-xl bg-white border border-gray-300 hover:border-amber-500 font-bold text-gray-700 hover:text-amber-700 transition-all shadow-2xs">
                        📦 إدارة باقات وكراتين التوفير (Bundles)
                    </a>
                </div>
            </div>

        </div>

        <!-- Sticky Save Floating Bar -->
        <div class="sticky bottom-4 z-30 bg-white/95 backdrop-blur-md border border-gray-200 rounded-3xl p-4 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-medium">يتم تطبيق التعديلات فوراً على المتجر المباشر ومسح الكاش تلقائياً.</span>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-black transition-all shadow-md shadow-primary-900/20 hover:scale-[1.01] active:scale-[0.98] cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('حفظ وتطبيق جميع الإعدادات الآن') }}</span>
            </button>
        </div>

    </form>

</div>
@endsection
