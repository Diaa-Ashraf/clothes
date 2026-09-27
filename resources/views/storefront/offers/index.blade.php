{{-- resources/views/storefront/offers/index.blade.php --}}
@extends('layouts.storefront')

@php
    // If an active magazine edition exists from the unified system:
    if (isset($activeMagazine) && $activeMagazine) {
        $magTitle = $activeMagazine->title;
        $magBadge = $activeMagazine->badge_text ?: 'إصدار مجلة التوفير الأسبوعية • سارية حتى نفاذ الكمية';
        $magSubtitle = $activeMagazine->subtitle ?: 'تصفح كل عروض الأسبوع، كراتين التوفير، الخصومات الحصرية على الألبان الفلاحي الطازجة وسلع التموين بفروع العامرية الأربعة.';
        $magBranchNote = $activeMagazine->branch_note ?: 'متاحة بجميع الفروع والتوصيل المنزلي';
        $magCountdownEnabled = (bool)$activeMagazine->show_countdown;

        $startDateText = $activeMagazine->starts_at ? $activeMagazine->starts_at->translatedFormat('d F') : now()->startOfWeek()->translatedFormat('d F');
        $endDateText = $activeMagazine->ends_at ? $activeMagazine->ends_at->translatedFormat('d F Y') : now()->addDays(6)->translatedFormat('d F Y');
        $targetIsoDate = $activeMagazine->ends_at ? $activeMagazine->ends_at->toIso8601String() : null;
    } else {
        // Fallback to Settings / Weekly default
        $magTitle = settings('magazine_title', 'مجلة عروض ألبان وسوبرماركت التوحيد');
        $magBadge = settings('magazine_badge', 'إصدار مجلة التوفير الأسبوعية • سارية حتى نفاذ الكمية');
        $magSubtitle = settings('magazine_subtitle', 'تصفح كل عروض الأسبوع، كراتين التوفير، الخصومات الحصرية على الألبان الفلاحي الطازجة وسلع التموين بفروع العامرية الأربعة.');
        $magBranchNote = settings('magazine_branch_note', 'متاحة بجميع الفروع والتوصيل المنزلي');
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

@section('title', $magTitle . ' | أقوى تخفيضات الألبان والبقالة بالعامرية')

@section('content')
<div class="bg-gradient-to-b from-orange-50/40 via-canvas-base to-canvas-base min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-red-600 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-gray-900 font-black">{{ $magTitle }}</span>
        </nav>

        <!-- Magazine Cover Hero with Active Dates & Realtime Countdown -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-800 via-red-600 to-orange-500 text-white p-6 sm:p-10 shadow-2xl border-2 border-orange-300">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-xs font-black text-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-300 animate-ping"></span>
                        <span>{{ $magBadge }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black leading-tight tracking-tight drop-shadow-md">
                        {{ $magTitle }}
                    </h1>

                    <p class="text-xs sm:text-sm md:text-base text-white/95 leading-relaxed font-medium">
                        {{ $magSubtitle }}
                    </p>

                    <!-- Magazine Validity Period Badge -->
                    <div class="flex flex-wrap items-center gap-3 pt-2 text-xs">
                        <div class="flex items-center gap-2 bg-black/25 backdrop-blur-xs px-3.5 py-2 rounded-xl border border-white/20">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-bold">فترة العرض: من <strong class="text-amber-300">{{ $startDateText }}</strong> حتى <strong class="text-amber-300">{{ $endDateText }}</strong></span>
                        </div>
                        @if(!empty($magBranchNote))
                        <div class="flex items-center gap-2 bg-black/25 backdrop-blur-xs px-3.5 py-2 rounded-xl border border-white/20">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-bold">{{ $magBranchNote }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                @if($magCountdownEnabled)
                <!-- Live Countdown Magazine Box -->
                <div class="bg-black/35 backdrop-blur-md border-2 border-amber-400/40 rounded-3xl p-6 text-center min-w-[280px] space-y-4 shadow-2xl">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-300 animate-spin" style="animation-duration: 6s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-black text-amber-300 tracking-wide uppercase">العداد التنازلي لانتهاء العروض</span>
                    </div>

                    <div class="grid grid-cols-4 gap-2 text-center" id="magazineCountdown">
                        <div class="bg-red-950/80 rounded-2xl p-2.5 border border-amber-400/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magDays">00</span>
                            <span class="text-[10px] text-gray-300 font-bold">أيام</span>
                        </div>
                        <div class="bg-red-950/80 rounded-2xl p-2.5 border border-amber-400/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magHours">00</span>
                            <span class="text-[10px] text-gray-300 font-bold">ساعة</span>
                        </div>
                        <div class="bg-red-950/80 rounded-2xl p-2.5 border border-amber-400/30">
                            <span class="text-xl sm:text-2xl font-black text-white block tabular-nums" id="magMinutes">00</span>
                            <span class="text-[10px] text-gray-300 font-bold">دقيقة</span>
                        </div>
                        <div class="bg-red-950/80 rounded-2xl p-2.5 border border-amber-400/30">
                            <span class="text-xl sm:text-2xl font-black text-amber-300 block tabular-nums animate-pulse" id="magSeconds">00</span>
                            <span class="text-[10px] text-gray-300 font-bold">ثانية</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="#magazine-deals" class="inline-block w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-gray-950 font-black text-xs shadow-md transition-transform hover:scale-105">
                            تصفح عروض المجلة ↓
                        </a>
                    </div>
                </div>
                @endif

            </div>

            <!-- Decorative shapes -->
            <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-red-500/30 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- ============================================================== -->
        <!-- 1. EXCLUSIVE MAGAZINE PRODUCTS (عروض المجلة المختارة)          -->
        <!-- ============================================================== -->
        @if(isset($activeMagazine) && $activeMagazine && $activeMagazine->items->isNotEmpty())
        <div id="magazine-deals" class="space-y-6 scroll-mt-24">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-orange-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-600 to-orange-500 text-white flex items-center justify-center font-black text-lg shadow-md">
                        📰
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900">عروض المجلة الحصرية</h2>
                        <p class="text-xs text-gray-500">المنتجات والخصومات الرسمية الخاصة بإصدار هذا الأسبوع</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-700 border border-red-200">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                    <span>{{ $activeMagazine->items->count() }} عرض حصري بالمجلة</span>
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($activeMagazine->items as $item)
                    @if($item->product)
                    @php
                        // Override product discount price with magazine item deal price for accurate card render
                        $magProduct = clone $item->product;
                        $magProduct->price = $item->original_price;
                        $magProduct->discount_price = $item->deal_price;
                    @endphp
                    <div class="relative flex flex-col h-full">
                        @if($item->badge_text)
                        <div class="mb-1.5 px-2.5 py-1 rounded-lg bg-red-50 border border-red-200 text-red-700 text-[10px] font-black flex items-center justify-between">
                            <span>✨ {{ $item->badge_text }}</span>
                            @if($item->savings_amount > 0)
                                <span class="text-emerald-700 font-bold">وفر {{ $item->formatted_savings }} ج.م</span>
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-orange-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-600 to-orange-500 text-white flex items-center justify-center font-black text-lg shadow-md">
                        ⚡
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900">عروض الصفقات الخاطفة (Flash Deals)</h2>
                        <p class="text-xs text-gray-500">أسعار خاصة لفترة محدودة مع عداد تنازلي لكل صنف</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-700 border border-red-200">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                    <span>{{ $deals->count() }} عروض حصرية متاحة</span>
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($deals as $deal)
                    @if($deal->product)
                    <div class="relative flex flex-col h-full">
                        @if($deal->ends_at)
                        <div class="mb-1.5 px-2.5 py-1 rounded-lg bg-red-50 border border-red-200 text-red-700 text-[10px] font-bold flex items-center justify-between">
                            <span>ينتهي خلال:</span>
                            <span class="font-black font-mono" dir="ltr">{{ $deal->ends_at->diffForHumans(['parts' => 2, 'short' => true]) }}</span>
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
        <!-- 2. FAMILY VALUE BUNDLES (كراتين وباقات التوفير العائلية)       -->
        <!-- ============================================================== -->
        @if($bundles->isNotEmpty())
        <div class="space-y-6 pt-4">
            <div class="flex items-center justify-between border-b border-orange-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center font-black text-lg shadow-md">
                        📦
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900">كراتين وباقات التوفير المجمعة</h2>
                        <p class="text-xs text-gray-500">وفر حتى 20% عند شراء كرتونة الطلبات الشهرية والألبان معاً</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bundles as $bundle)
                <div class="bg-white rounded-3xl border-2 border-orange-100 hover:border-orange-400 p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-4 hover:-translate-y-1">
                    <div class="space-y-3.5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-xs">
                                    خصم {{ $bundle->discount_percent }}%
                                </span>
                                <h3 class="text-base font-black text-gray-900 mt-2">{{ $bundle->name }}</h3>
                            </div>
                            @if($bundle->image_url)
                                <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="w-20 h-20 object-contain rounded-2xl bg-orange-50/50 p-1.5 border border-orange-100 shrink-0">
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 font-medium">
                            {{ $bundle->description ?: 'باقة توفير مميزة تشمل أهم الأصناف الأساسية بأفضل جودة وسعر مخفض.' }}
                        </p>

                        <!-- Included items breakdown -->
                        @if($bundle->products->isNotEmpty())
                        <div class="bg-gradient-to-br from-orange-50/60 to-red-50/40 rounded-2xl p-3.5 space-y-2 border border-orange-100">
                            <span class="text-[11px] font-black text-gray-900 block">محتويات الباقة:</span>
                            <ul class="text-xs text-gray-600 space-y-1.5">
                                @foreach($bundle->products->take(4) as $p)
                                <li class="flex items-center justify-between text-[11px]">
                                    <span class="font-medium">• {{ $p->name }}</span>
                                    @if(isset($p->pivot->quantity) && $p->pivot->quantity > 1)
                                        <span class="font-black text-orange-600">({{ $p->pivot->quantity }} قطع)</span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Pricing & Action Button -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-black text-red-600 tabular-nums">{{ $bundle->formatted_bundle_price }}</span>
                                @if($bundle->original_total > $bundle->bundle_price)
                                    <span class="text-xs text-gray-400 line-through tabular-nums">{{ $bundle->formatted_original_total }}</span>
                                @endif
                            </div>
                            <span class="text-[11px] text-emerald-600 font-black block">
                                وفرت {{ number_format($bundle->original_total - $bundle->bundle_price, 2) }} ج.م
                            </span>
                        </div>

                        <button type="button" 
                                onclick="window.addBundleToCart({{ $bundle->id }}, this)"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black transition-all shadow-md hover:shadow-orange-500/30 flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>أضف الباقة</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ============================================================== -->
        <!-- 3. ALL DISCOUNTED PRODUCTS IN SUPERMARKET (باقي الخصومات)     -->
        <!-- ============================================================== -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-orange-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-black text-lg shadow-md">
                        🏷️
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900">كل المنتجات المخفضة في المتجر</h2>
                        <p class="text-xs text-gray-500">تخفيضات مستمرة على جميع أصناف السوبرماركت والألبان</p>
                    </div>
                </div>
                <span class="text-xs font-black text-gray-700 bg-white px-3.5 py-2 rounded-xl border border-orange-200 shadow-xs">
                    {{ $discountedProducts->total() }} صنف مخفض حالياً
                </span>
            </div>

            @if($discountedProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-6">
                @foreach($discountedProducts as $product)
                    <x-storefront.ui.product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $discountedProducts->links() }}
            </div>
            @else
            <div class="bg-white rounded-3xl border border-cream p-12 text-center space-y-4 shadow-sm">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-primary-50 text-primary-800 flex items-center justify-center">
                    🛍️
                </div>
                <h3 class="text-base font-bold text-charcoal-heading">عروض جديدة يتم تحضيرها الآن!</h3>
                <p class="text-xs text-charcoal-muted max-w-sm mx-auto">
                    نقوم بإعداد خصومات وتخفيضات طازجة على منتجات الألبان والأجبان. تفضل بتصفح المتجر بالكامل للاطلاع على المنتجات.
                </p>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary-800 text-white text-xs font-bold hover:bg-primary-900 transition-colors">
                    تصفح المتجر بالكامل
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
            // Default to next Sunday 23:59:59
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
            btn.innerHTML = `<span>✓</span> تم بنجاح`;
            btn.classList.remove('from-red-600', 'to-orange-500');
            btn.classList.add('bg-emerald-600');

            if (window.toastr) {
                toastr.success(response.message || 'تمت إضافة الباقة لسلتك بنجاح');
            }

            if (response.cart_count) {
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: response.cart_count } }));
                const badge = document.getElementById('cart-badge-count');
                if (badge) badge.innerText = response.cart_count;
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                btn.classList.remove('bg-emerald-600');
                btn.classList.add('from-red-600', 'to-orange-500');
            }, 2000);
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (window.toastr) {
                toastr.error('حدث خطأ أثناء إضافة الباقة');
            }
        });
    };
</script>
@endpush
@endsection
