{{-- resources/views/components/storefront/layout/footer.blade.php --}}
<footer class="bg-[#2B2118] text-white pt-14 pb-10 border-t-4 border-[#AC9653] select-none" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Footer Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 pb-10 border-b border-white/15 text-right">
            
            <!-- Column 1: Store Brand & About (Dynamic from Settings) -->
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    @if(get_site_logo())
                        <div class="h-10 max-w-[130px] flex items-center justify-center shrink-0">
                            <img src="{{ get_site_logo() }}" alt="{{ settings('site_name', 'أزياء لافيستا') }}" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <span class="w-9 h-9 rounded-xl bg-[#AC9653] text-white font-black text-lg flex items-center justify-center shadow-xs">
                            {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                        </span>
                    @endif
                    <div>
                        <span class="text-xl font-black text-white tracking-tight">{{ settings('site_name', 'أزياء لافيستا') }}</span>
                        <span class="block text-[10px] text-[#CBA367] tracking-wider uppercase">{{ settings('site_tagline', 'LAVISTA FASHION') }}</span>
                    </div>
                </div>

                <p class="text-xs text-gray-300 leading-relaxed font-normal">
                    {{ settings('meta_description', 'متجر ومحل مصري متخصص في بيع أرقى الملابس الجاهزة للرجال والنساء والأطفال. نعتمد أعلى معايير الجودة وخامات القطن المصري طويل التيلة بأسعار تنافسية تلائم كل بيت مصري.') }}
                </p>

                <div class="space-y-2 text-xs text-gray-300 pt-1">
                    <p class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#CBA367] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ settings('store_address', 'فروعنا: الإسكندرية • القاهرة • طنطا') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#CBA367] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
                        </svg>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', settings('store_phone', '01000000000')) }}" class="font-bold text-white hover:text-[#CBA367] transition-colors" dir="ltr">
                            {{ settings('store_phone', '01000000000') }}
                            @if(settings('store_phone_secondary'))
                                / {{ settings('store_phone_secondary') }}
                            @endif
                        </a>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#CBA367] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ settings('store_email', 'support@lavista-fashion.com') }}" class="hover:text-[#CBA367] transition-colors">
                            {{ settings('store_email', 'support@lavista-fashion.com') }}
                        </a>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#CBA367] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>مواعيد العمل: {{ settings('working_hours', 'يومياً من 10 صباحاً إلى 11 مساءً') }}</span>
                    </p>
                </div>

                <!-- Social Media Channels Strip (Dynamic from Settings) -->
                <div class="pt-2 flex items-center gap-2 flex-wrap">
                    @if(settings('facebook_link'))
                    <a href="{{ settings('facebook_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#1877F2] text-white flex items-center justify-center transition-all hover:scale-105" title="فيسبوك" aria-label="فيسبوك">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif

                    @if(settings('instagram_link'))
                    <a href="{{ settings('instagram_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-gradient-to-tr hover:from-[#F58529] hover:via-[#DD2A7B] hover:to-[#8134AF] text-white flex items-center justify-center transition-all hover:scale-105" title="إنستغرام" aria-label="إنستغرام">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif

                    @if(settings('tiktok_link'))
                    <a href="{{ settings('tiktok_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-black text-white flex items-center justify-center transition-all hover:scale-105" title="تيك توك" aria-label="تيك توك">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                    @endif

                    @if(settings('youtube_link'))
                    <a href="{{ settings('youtube_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#FF0000] text-white flex items-center justify-center transition-all hover:scale-105" title="يوتيوب" aria-label="يوتيوب">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif

                    @if(settings('twitter_link'))
                    <a href="{{ settings('twitter_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-black text-white flex items-center justify-center transition-all hover:scale-105" title="منصة X" aria-label="منصة X">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif

                    @if(settings('telegram_link'))
                    <a href="{{ settings('telegram_link') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#229ED9] text-white flex items-center justify-center transition-all hover:scale-105" title="تيليجرام" aria-label="تيليجرام">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                    </a>
                    @endif

                    @php
                        $waNum = settings('store_whatsapp') ?: settings('store_phone');
                    @endphp
                    @if($waNum)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNum) }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#25D366] text-white flex items-center justify-center transition-all hover:scale-105" title="واتساب" aria-label="واتساب">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Quick Shop Categories Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#CBA367] pb-1 border-b border-white/10">
                    أقسام الملابس
                </h4>
                <ul class="space-y-2 text-xs text-gray-300 font-medium">
                    <li>
                        <a href="{{ route('shop', ['category_id' => 9]) }}" class="hover:text-[#CBA367] transition-colors">
                            ملابس رجالية (قمصان، بدل، جينز)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['category_id' => 14]) }}" class="hover:text-[#CBA367] transition-colors">
                            أزياء نسائية (فساتين، بلوزات، عبايات)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['category_id' => 19]) }}" class="hover:text-[#CBA367] transition-colors">
                            أطفال ومواليد (أولاد وبنات)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['category_id' => 13]) }}" class="hover:text-[#CBA367] transition-colors">
                            معاطف وجواكت شتوية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['category_id' => 22]) }}" class="hover:text-[#CBA367] transition-colors">
                            أحذية وحقائب جلدية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['category_id' => 25]) }}" class="hover:text-[#CBA367] transition-colors">
                            إكسسوارات وساعات وأحزمة
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop', ['deals_only' => 1]) }}" class="text-[#CBA367] font-bold hover:underline flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>أقوى العروض والتخفيضات اليومية</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Customer Care & Policies -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#CBA367] pb-1 border-b border-white/10">
                    خدمة العملاء والضمان
                </h4>
                <ul class="space-y-2 text-xs text-gray-300 font-medium">
                    <li>
                        <a href="{{ route('orders.track') }}" class="hover:text-[#CBA367] transition-colors">
                            تتبع حالة طلبك
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.return-policy') }}" class="hover:text-[#CBA367] transition-colors">
                            سياسة الاستبدال والاسترجاع (14 يوماً)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.terms') }}" class="hover:text-[#CBA367] transition-colors">
                            الشحن والتوصيل لجميع المحافظات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('branches.index') }}" class="hover:text-[#CBA367] transition-colors">
                            فروع ومتاجر {{ settings('site_name', 'أزياء لافيستا') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-[#CBA367] transition-colors">
                            تواصل معنا واقتراحات العملاء
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.privacy') }}" class="hover:text-[#CBA367] transition-colors">
                            سياسة الخصوصية وأمان البيانات
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Egyptian Payment Methods & Guarantees -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#CBA367] pb-1 border-b border-white/10">
                    طرق الدفع والشحن المعتمدة
                </h4>
                <p class="text-xs text-gray-300 leading-relaxed font-normal">
                    نوفر لك أسهل وأأمن خيارات الدفع في السوق المصري لراحتك التامة:
                </p>

                <!-- Payment Chips -->
                <div class="flex flex-wrap gap-2 text-[11px] font-bold">
                    <span class="px-2.5 py-1 bg-white/10 rounded border border-white/20 text-white">الدفع عند الاستلام</span>
                    <span class="px-2.5 py-1 bg-white/10 rounded border border-white/20 text-white">إنستاباي (InstaPay)</span>
                    <span class="px-2.5 py-1 bg-white/10 rounded border border-white/20 text-white">فودافون كاش</span>
                    <span class="px-2.5 py-1 bg-white/10 rounded border border-white/20 text-white">بطاقة ميزة ومحافظ إلكترونية</span>
                    <span class="px-2.5 py-1 bg-white/10 rounded border border-white/20 text-white">فيزا وماستركارد</span>
                </div>

                <div class="p-3 bg-white/5 rounded-xl border border-white/10 space-y-1 text-right">
                    <span class="block text-xs font-bold text-[#CBA367]">معاينة وقياس الملابس مجاناً:</span>
                    <span class="block text-[11px] text-gray-300">حقك كعميل تقيس وتتأكد من الخامة والمقاس قبل الاستلام مع مندوب الشحن.</span>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright & Compliance Strip -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400 text-center sm:text-right">
            <div>
                © {{ date('Y') }} <strong>{{ settings('site_name', 'أزياء لافيستا') }}</strong> — كافة الحقوق محفوظة. صُنع بكل فخر في جمهورية مصر العربية.
            </div>
            <div class="flex items-center gap-4 text-xs">
                <a href="{{ route('pages.terms') }}" class="hover:text-white transition-colors">الشروط والأحكام</a>
                <span>•</span>
                <a href="{{ route('pages.privacy') }}" class="hover:text-white transition-colors">الخصوصية</a>
                <span>•</span>
                <a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">خريطة الموقع</a>
            </div>
        </div>

    </div>
</footer>
