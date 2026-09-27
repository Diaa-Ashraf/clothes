{{-- resources/views/components/storefront/layout/footer.blade.php --}}
@php
    $siteName = settings('site_name', 'ألبان وسوبرماركت التوحيد');
    $siteTagline = settings('site_tagline', 'أصالة الطعم البلدي وجودة السوبرماركت');
    $storePhone = settings('store_phone', '01000000000');
    $storePhoneSecondary = settings('store_phone_secondary', '');
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $storeEmail = settings('store_email', 'info@altawheed-dairy.com');
    $address = settings('address', 'العامرية - الإسكندرية (4 فروع في خدمتك)');
    $workingHours = settings('working_hours', 'يومياً: من 8:00 صباحاً حتى 2:00 بعد منتصف الليل');
    $aboutShort = settings('about_us_short', 'من أكبر مراكز الألبان والسوبرماركت في منطقة العامرية والإسكندرية. نوفر أجود منتجات الألبان الطازجة، الأجبان الطبيعية، والسلع الغذائية بأسعار الجملة والتجزئة وخدمة توصيل سريعة حتى باب بيتك.');
    
    $facebookLink = settings('facebook_link');
    $instagramLink = settings('instagram_link');
    $tiktokLink = settings('tiktok_link');
    $youtubeLink = settings('youtube_link');

    $enableCod = settings('enable_cod', '1') == '1';
    $enableInstapay = settings('enable_instapay', '1') == '1';
    $enableVodafoneCash = settings('enable_vodafone_cash', '1') == '1';
@endphp

<footer class="bg-charcoal-heading text-white/90 pt-12 pb-8 border-t-4 border-primary-700">
    <div class="container mx-auto px-4">
        <!-- Top Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 pb-10 border-b border-white/10">
            
            <!-- Column 1: Brand & Bio -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    @if(get_site_logo())
                    <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-lg shrink-0">
                        <img src="{{ get_site_logo() }}" alt="{{ $siteName }}" class="max-h-full max-w-full object-contain">
                    </div>
                    @else
                    <div class="w-10 h-10 rounded-xl bg-primary-800 text-white flex items-center justify-center font-black text-xl shadow-lg shrink-0">
                        ت
                    </div>
                    @endif
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight">{{ $siteName }}</h3>
                        <p class="text-xs text-accent-400 font-medium">{{ $siteTagline }}</p>
                    </div>
                </div>

                <p class="text-xs text-white/70 leading-relaxed">
                    {{ $aboutShort }}
                </p>

                <!-- Social Links Grid -->
                <div class="pt-2 flex flex-wrap items-center gap-2.5">
                    @if(!empty($facebookLink))
                    <a href="{{ $facebookLink }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors font-bold text-xs" title="فيسبوك">
                        f
                    </a>
                    @endif

                    @if(!empty($instagramLink))
                    <a href="{{ $instagramLink }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-pink-600/20 text-pink-400 hover:bg-pink-600 hover:text-white flex items-center justify-center transition-colors text-xs" title="إنستغرام">
                        📷
                    </a>
                    @endif

                    @if(!empty($tiktokLink))
                    <a href="{{ $tiktokLink }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-white/10 text-white hover:bg-white hover:text-gray-900 flex items-center justify-center transition-colors text-xs" title="تيك توك">
                        🎵
                    </a>
                    @endif

                    @if(!empty($youtubeLink))
                    <a href="{{ $youtubeLink }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors text-xs" title="يوتيوب">
                        ▶
                    </a>
                    @endif

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeWhatsApp) }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors" title="واتساب">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                        </svg>
                    </a>

                    <a href="tel:{{ $storePhone }}" class="w-8 h-8 rounded-full bg-primary-800/40 text-primary-300 hover:bg-primary-700 hover:text-white flex items-center justify-center transition-colors" title="اتصل بنا">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-white border-r-2 border-accent-500 pr-2">روابط سريعة</h4>
                <ul class="space-y-2 text-xs text-white/70">
                    <li><a href="{{ route('site.home') }}" class="hover:text-accent-400 transition-colors">الرئيسية</a></li>
                    <li><a href="{{ route('site.products') }}" class="hover:text-accent-400 transition-colors">تصفح كل المنتجات</a></li>
                    <li><a href="{{ route('offers.index') }}" class="hover:text-accent-400 transition-colors">مجلة عروض التوفير</a></li>
                    <li><a href="{{ route('branches.index') }}" class="hover:text-accent-400 transition-colors">فروع ألبان التوحيد بالعامرية</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-accent-400 transition-colors">عن التوحيد وتاريخنا</a></li>
                    <li><a href="{{ route('careers.index') }}" class="hover:text-accent-400 transition-colors">فرص العمل والتوظيف</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact & Delivery -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-white border-r-2 border-accent-500 pr-2">التواصل والفروع</h4>
                <ul class="space-y-2.5 text-xs text-white/70">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-accent-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <span>{{ $address }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span dir="ltr">{{ $storePhone }}</span>
                        @if($storePhoneSecondary)
                        <span class="text-white/40">|</span>
                        <span dir="ltr">{{ $storePhoneSecondary }}</span>
                        @endif
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $storeEmail }}</span>
                    </li>
                    <li class="flex items-center gap-2 text-emerald-400 font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $workingHours }}</span>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Payment Methods & Guarantees -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-white border-r-2 border-accent-500 pr-2">طرق الدفع المعتمدة</h4>
                <p class="text-xs text-white/70">
                    نوفر لك خيارات دفع مريحة وآمنة تناسبك:
                </p>
                <div class="space-y-2">
                    @if($enableCod)
                    <!-- Cash on delivery -->
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">الدفع نقداً عند الاستلام</p>
                            <p class="text-[10px] text-white/60">ادفع للمندوب أو بالفرع بعد فحص طلبك</p>
                        </div>
                    </div>
                    @endif

                    @if($enableInstapay)
                    <!-- InstaPay -->
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-md bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">تحويل إنستاباي (InstaPay)</p>
                            <p class="text-[10px] text-white/60">تحويل مباشر وسريع ولحظي</p>
                        </div>
                    </div>
                    @endif

                    @if($enableVodafoneCash)
                    <!-- Vodafone Cash -->
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-md bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">فودافون كاش / المحافظ</p>
                            <p class="text-[10px] text-white/60">تحويل سريع عبر محفظة الهاتف</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Bottom Copyright -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/60">
            <p>
                جميع الحقوق محفوظة © {{ date('Y') }} — <strong class="text-white">{{ $siteName }}</strong> (العامرية، الإسكندرية)
            </p>
            <div class="flex flex-wrap items-center gap-3 text-[11px]">
                <a href="{{ route('pages.terms') }}" class="hover:text-white transition-colors">الشروط والأحكام</a>
                <span>•</span>
                <a href="{{ route('pages.privacy') }}" class="hover:text-white transition-colors">سياسة الخصوصية</a>
                <span>•</span>
                <a href="{{ route('pages.return-policy') }}" class="hover:text-white transition-colors">سياسة الاسترجاع</a>
                <span>•</span>
                <a href="{{ route('contact.index') }}" class="hover:underline">خدمة العملاء</a>
            </div>
        </div>
    </div>
</footer>
