{{-- resources/views/storefront/offers/index.blade.php --}}
@extends('layouts.storefront')

@php
    if (isset($activeMagazine) && $activeMagazine) {
        $magTitle = $activeMagazine->title;
        $magBadge = $activeMagazine->badge_text ?: ('كتالوج وتخفيضات ' . settings('site_name', 'أزياء لافيستا') . ' • سارية حتى نفاذ الكمية');
        $magSubtitle = $activeMagazine->subtitle ?: 'تصفح أقوى عروض الموسم، تخفيضات الأطقم وتنسيقات الملابس الكاملة، والخصومات الحصرية بفروعنا وعبر المتجر الإلكتروني.';
        $magBranchNote = $activeMagazine->branch_note ?: 'متاحة بجميع فروعنا والشحن الفوري';
        $magCountdownEnabled = (bool)$activeMagazine->show_countdown;

        $startDateText = $activeMagazine->starts_at ? $activeMagazine->starts_at->translatedFormat('d F') : now()->startOfWeek()->translatedFormat('d F');
        $endDateText = $activeMagazine->ends_at ? $activeMagazine->ends_at->translatedFormat('d F Y') : now()->addDays(6)->translatedFormat('d F Y');
        $targetIsoDate = $activeMagazine->ends_at ? $activeMagazine->ends_at->toIso8601String() : null;
    } else {
        $magTitle = settings('magazine_title', 'مجلة وتخفيضات ' . settings('site_name', 'أزياء لافيستا'));
        $magBadge = settings('magazine_badge', 'تخفيضات الموسم الحصرية • سارية حتى نفاذ الكمية');
        $magSubtitle = settings('magazine_subtitle', 'تصفح أقوى عروض الموسم، تخفيضات الأطقم وتنسيقات الملابس الكاملة، والخصومات الحصرية بفروعنا وعبر المتجر الإلكتروني.');
        $magBranchNote = settings('magazine_branch_note', 'متاحة بجميع فروعنا والشحن الفوري');
        $magTimingMode = settings('magazine_timing_mode', 'auto_weekly');
        $magStartDate = settings('magazine_start_date');
        $magEndDate = settings('magazine_end_date');
        $magCountdownEnabled = settings('magazine_countdown_enabled', '1') == '1';

        $isCustom = ($magTimingMode === 'custom' && !empty($magEndDate));

        if ($isCustom) {
            $startDateText = !empty($magStartDate) ? \Carbon\Carbon::parse($magStartDate)->translatedFormat('d F') : now()->translatedFormat('d F');
            $endDateText = \Carbon\Carbon::parse($magEndDate)->translatedFormat('d F Y');
            $targetIsoDate = \Carbon\Carbon::parse($magEndDate)->toIso8601String();
        } else {
            $startDateText = now()->startOfWeek()->translatedFormat('d F');
            $endDateText = now()->addDays(6)->translatedFormat('d F Y');
            $targetIsoDate = null;
        }
    }
@endphp

@section('title', $magTitle . ' | أقوى تخفيضات الأزياء والموضة')

@section('content')
<div class="bg-[#FBF6EA] min-h-screen py-8" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">{{ $magTitle }}</span>
        </nav>

        <!-- Magazine Cover Hero with Active Dates & Realtime Countdown -->
        <div class="relative overflow-hidden rounded-3xl bg-[#2B2118] text-[#FBF6EA] p-6 sm:p-10 shadow-lg border border-[#CDB78F]/30">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] text-xs font-bold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                        <span>{{ $magBadge }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif font-black leading-tight tracking-tight text-[#FBF6EA]">
                        {{ $magTitle }}
                    </h1>

                    <p class="text-xs sm:text-sm md:text-base text-[#F3E9D2]/90 leading-relaxed font-normal">
                        {{ $magSubtitle }}
                    </p>

                    <!-- Validity Period Badge -->
                    <div class="flex flex-wrap items-center gap-3 pt-2 text-xs">
                        <div class="flex items-center gap-2 bg-black/30 backdrop-blur-xs px-3.5 py-2 rounded-xl border border-white/15">
                            <svg class="w-4 h-4 text-[#CDB78F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>فترة العرض: من <strong class="text-[#CDB78F]">{{ $startDateText }}</strong> حتى <strong class="text-[#CDB78F]">{{ $endDateText }}</strong></span>
                        </div>
                        @if(!empty($magBranchNote))
                        <div class="flex items-center gap-2 bg-black/30 backdrop-blur-xs px-3.5 py-2 rounded-xl border border-white/15">
                            <svg class="w-4 h-4 text-[#CDB78F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>{{ $magBranchNote }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                @if($magCountdownEnabled)
                <!-- Live Countdown Magazine Box -->
                <div class="bg-black/30 backdrop-blur-md border border-[#CDB78F]/40 rounded-3xl p-6 text-center min-w-[280px] space-y-4 shadow-xl">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[#CDB78F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-bold text-[#CDB78F] tracking-wide uppercase">العداد التنازلي لانتهاء التخفيضات</span>
                    </div>

                    <div class="grid grid-cols-4 gap-2 text-center" id="magazineCountdown">
                        <div class="bg-black/40 rounded-2xl p-2.5 border border-[#CDB78F]/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magDays">00</span>
                            <span class="text-[10px] text-[#F3E9D2] font-semibold">أيام</span>
                        </div>
                        <div class="bg-black/40 rounded-2xl p-2.5 border border-[#CDB78F]/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magHours">00</span>
                            <span class="text-[10px] text-[#F3E9D2] font-semibold">ساعة</span>
                        </div>
                        <div class="bg-black/40 rounded-2xl p-2.5 border border-[#CDB78F]/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magMinutes">00</span>
                            <span class="text-[10px] text-[#F3E9D2] font-semibold">دقيقة</span>
                        </div>
                        <div class="bg-black/40 rounded-2xl p-2.5 border border-[#CDB78F]/30">
                            <span class="text-xl sm:text-2xl font-black text-[#CDB78F] block tabular-nums" id="magSeconds">00</span>
                            <span class="text-[10px] text-[#F3E9D2] font-semibold">ثانية</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="#magazine-deals" class="inline-block w-full py-2.5 px-4 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#382416] font-bold text-xs shadow-xs transition-transform hover:scale-102">
                            تصفح العروض الآن ↓
                        </a>
                    </div>
                </div>
                @endif

            </div>

            <!-- Decorative shapes -->
            <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#CDB78F]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-[#A9552F]/20 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- ============================================================== -->
        <!-- 1. EXCLUSIVE MAGAZINE PRODUCTS                                -->
        <!-- ============================================================== -->
        @if(isset($activeMagazine) && $activeMagazine && $activeMagazine->items->isNotEmpty())
        <div id="magazine-deals" class="space-y-6 scroll-mt-24">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EADFC8] pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center font-bold text-lg shadow-xs">
                        <svg class="w-5 h-5 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">عروض التشكيلة الحصرية</h2>
                        <p class="text-xs text-[#8F7B63]">القطع والتخفيضات الرسمية الخاصة بهذا الكتالوج</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F]">
                    <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                    <span>{{ $activeMagazine->items->count() }} عرض حصري متاح</span>
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($activeMagazine->items as $item)
                    @if($item->product)
                    @php
                        $magProduct = clone $item->product;
                        $magProduct->price = $item->original_price;
                        $magProduct->discount_price = $item->deal_price;
                    @endphp
                    <div class="relative flex flex-col h-full">
                        @if($item->badge_text)
                        <div class="mb-1.5 px-2.5 py-1 rounded-lg bg-[#F3E9D2] border border-[#EADFC8] text-[#382416] text-[10px] font-bold flex items-center justify-between">
                            <span>{{ $item->badge_text }}</span>
                            @if($item->savings_amount > 0)
                                <span class="text-[#A9552F] font-bold">وفر {{ $item->formatted_savings }} ج.م</span>
                            @endif
                        </div>
                        @endif
                        <x-storefront.ui.product-card :product="$magProduct" />
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        @elseif($deals->isNotEmpty())
        <!-- Standalone Flash Deals (Fallback) -->
        <div id="magazine-deals" class="space-y-6 scroll-mt-24">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EADFC8] pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center font-bold text-lg shadow-xs">
                        <svg class="w-5 h-5 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">صفقات الموضة الخاطفة (Flash Deals)</h2>
                        <p class="text-xs text-[#8F7B63]">أسعار خاصة لفترة محدودة على تشكيلات مختارة</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F]">
                    <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                    <span>{{ $deals->count() }} عروض خاصة متاحة</span>
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($deals as $deal)
                    @if($deal->product)
                    <div class="relative flex flex-col h-full">
                        @if($deal->ends_at)
                        <div class="mb-1.5 px-2.5 py-1 rounded-lg bg-[#F3E9D2] border border-[#EADFC8] text-[#382416] text-[10px] font-bold flex items-center justify-between">
                            <span>ينتهي العرض:</span>
                            <span class="font-bold font-mono" dir="ltr">{{ $deal->ends_at->diffForHumans(['parts' => 2, 'short' => true]) }}</span>
                        </div>
                        @endif
                        <x-storefront.ui.product-card :product="$deal->product" />
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- ============================================================== -->
        <!-- 2. FASHION OUTFIT BUNDLES (أطقم وتنسيقات الأزياء الكاملة)      -->
        <!-- ============================================================== -->
        @if($bundles->isNotEmpty())
        <div class="space-y-6 pt-4">
            <div class="flex items-center justify-between border-b border-[#EADFC8] pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center font-bold text-lg shadow-xs">
                        <svg class="w-5 h-5 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">أطقم وتنسيقات الملابس المجمعة (Bundles)</h2>
                        <p class="text-xs text-[#8F7B63]">وفر أكثر عند شراء الطقم الكامل بتنسيق أنيق ومتناسق</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bundles as $bundle)
                <div class="bg-[#FDFBF7] rounded-3xl border border-[#EADFC8] hover:border-[#CDB78F] p-5 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-4 hover:-translate-y-1">
                    <div class="space-y-4">
                        @if($bundle->image_url)
                            <div class="relative w-full h-44 rounded-2xl overflow-hidden bg-[#F3E9D2] border border-[#EADFC8] shadow-inner">
                                <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="w-full h-full object-cover object-center">
                                @if($bundle->discount_percent > 0)
                                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold bg-[#A9552F] text-[#FBF6EA] shadow-xs">
                                        وفر {{ $bundle->discount_percent }}%
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="relative w-full rounded-2xl bg-[#F3E9D2]/40 border border-[#EADFC8] p-3 shadow-inner">
                                <div class="flex items-center justify-center gap-2 flex-wrap py-2">
                                    @foreach($bundle->products->take(4) as $p)
                                        <div class="w-12 h-12 rounded-xl bg-white p-1 border border-[#EADFC8] shadow-2xs flex items-center justify-center overflow-hidden">
                                            <img src="{{ $p->image_url ?? asset('assets/site/img/product-placeholder.png') }}" alt="{{ $p->name }}" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        @if(!$loop->last)
                                            <span class="text-[#CDB78F] font-black text-xs">+</span>
                                        @endif
                                    @endforeach
                                </div>
                                @if($bundle->discount_percent > 0)
                                    <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#A9552F] text-[#FBF6EA] shadow-xs">
                                        وفر {{ $bundle->discount_percent }}%
                                    </span>
                                @endif
                            </div>
                        @endif

                        <div>
                            <h3 class="text-base font-serif font-bold text-[#2B2118]">{{ $bundle->name }}</h3>
                            <p class="text-xs text-[#8F7B63] leading-relaxed line-clamp-2 font-medium mt-1">
                                {{ $bundle->description ?: 'طقم ملابس متناسق يجمع بين الأناقة والراحة بسعر خاص ومخفض.' }}
                            </p>
                        </div>

                        <!-- Included items breakdown -->
                        @if($bundle->products->isNotEmpty())
                        <div class="bg-[#F3E9D2]/30 rounded-2xl p-3.5 space-y-2 border border-[#EADFC8]">
                            <span class="text-[11px] font-bold text-[#2B2118] block flex items-center gap-1.5">
                                <span>🛍️</span>
                                <span>القطع المشمولة في الطقم:</span>
                            </span>
                            <ul class="text-xs text-[#6B5642] space-y-1.5">
                                @foreach($bundle->products as $p)
                                <li class="flex items-center justify-between text-[11px]">
                                    <span class="font-medium text-[#2B2118]">• {{ $p->name }}</span>
                                    <span class="px-2 py-0.5 rounded-md bg-white border border-[#EADFC8] font-bold text-[10px] text-[#A9552F]">
                                        {{ $p->pivot->quantity ?? 1 }}x
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Pricing & Action Button -->
                    <div class="pt-4 border-t border-[#EADFC8] flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-black text-[#382416] tabular-nums">{{ $bundle->formatted_bundle_price }}</span>
                                @if($bundle->original_total > $bundle->bundle_price)
                                    <span class="text-xs text-[#8F7B63] line-through tabular-nums">{{ $bundle->formatted_original_total }}</span>
                                @endif
                            </div>
                            <span class="text-[11px] text-[#A9552F] font-bold block">
                                وفرت {{ number_format($bundle->original_total - $bundle->bundle_price, 2) }} ج.م
                            </span>
                        </div>

                        <button type="button" 
                                onclick="window.addBundleToCart({{ $bundle->id }}, this)"
                                class="px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-[#CDB78F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>أضف الطقم</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ============================================================== -->
        <!-- 3. ALL DISCOUNTED PRODUCTS IN BOUTIQUE                        -->
        <!-- ============================================================== -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EADFC8] pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center font-bold text-lg shadow-xs">
                        <svg class="w-5 h-5 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">جميع القطع المخفضة في البوتيك</h2>
                        <p class="text-xs text-[#8F7B63]">تخفيضات مستمرة على موديلات وتشكيلات مختارة</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-[#382416] bg-[#FDFBF7] px-3.5 py-2 rounded-xl border border-[#EADFC8] shadow-2xs">
                    {{ $discountedProducts->total() }} قطعة مخفضة حالياً
                </span>
            </div>

            @if($discountedProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($discountedProducts as $product)
                    <x-storefront.ui.product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6 flex justify-center">
                {{ $discountedProducts->links() }}
            </div>
            @else
            <div class="bg-[#FDFBF7] rounded-3xl border border-[#EADFC8] p-12 text-center space-y-4 shadow-xs">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-[#F3E9D2] text-[#382416] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-base font-serif font-bold text-[#2B2118]">تشكيلات مخفضة جديدة يتم تحضيرها الآن!</h3>
                <p class="text-xs text-[#8F7B63] max-w-sm mx-auto">
                    نقوم بإعداد خصومات وتخفيضات خاصة على أحدث تشكيلات الأزياء. تفضل بتصفح الكتالوج بالكامل للاطلاع على المنتجات.
                </p>
                <a href="{{ route('site.products') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#382416] text-[#FBF6EA] text-xs font-bold hover:bg-[#2B2118] transition-colors">
                    تصفح الكتالوج بالكامل
                </a>
            </div>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
    const targetIsoDate = @json($targetIsoDate);

    // Dynamic Magazine Countdown timer
    function updateMagazineCountdown() {
        const now = new Date();
        let targetDate;

        if (targetIsoDate) {
            targetDate = new Date(targetIsoDate);
        } else {
            targetDate = new Date();
            const day = now.getDay();
            const diffDays = 7 - (day === 0 ? 7 : day);
            targetDate.setDate(now.getDate() + diffDays);
            targetDate.setHours(23, 59, 59, 999);
        }

        const diff = targetDate - now;

        const dEl = document.getElementById('magDays');
        const hEl = document.getElementById('magHours');
        const mEl = document.getElementById('magMinutes');
        const sEl = document.getElementById('magSeconds');

        if (diff > 0) {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diff / 1000 / 60) % 60);
            const seconds = Math.floor((diff / 1000) % 60);

            if (dEl) dEl.textContent = String(days).padStart(2, '0');
            if (hEl) hEl.textContent = String(hours).padStart(2, '0');
            if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
            if (sEl) sEl.textContent = String(seconds).padStart(2, '0');
        } else {
            if (dEl) dEl.textContent = '00';
            if (hEl) hEl.textContent = '00';
            if (mEl) mEl.textContent = '00';
            if (sEl) sEl.textContent = '00';
        }
    }

    if (document.getElementById('magazineCountdown')) {
        setInterval(updateMagazineCountdown, 1000);
        updateMagazineCountdown();
    }

    // Bundle Add to Cart Helper
    window.addBundleToCart = function(bundleId, btn) {
        if (!bundleId) return;

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<span class="animate-spin inline-block mr-1">⌛</span> جاري الإضافة...`;

        fetch("{{ route('bundle.add-to-cart') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ bundle_id: bundleId })
        })
        .then(res => res.json())
        .then(response => {
            btn.innerHTML = `<span>✓</span> تمت الإضافة`;
            btn.classList.add('bg-[#382416]');

            if (window.toastr) {
                toastr.success(response.message || 'تمت إضافة الطقم إلى حقيبتك بنجاح');
            }

            if (response.cart_count) {
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: response.cart_count } }));
                const badge = document.getElementById('cart-badge-count');
                if (badge) badge.innerText = response.cart_count;
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 2000);
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (window.toastr) {
                toastr.error('حدث خطأ أثناء إضافة الطقم');
            }
        });
    };
</script>
@endpush
@endsection
