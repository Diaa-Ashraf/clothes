<!-- Luxury Admin Sidebar (Pure Tailwind CSS) -->
<aside class="w-72 flex-shrink-0 bg-white border-e border-[#EADBCC] flex flex-col h-screen sticky top-0 z-30 shadow-[2px_0_15px_rgba(0,0,0,0.02)]">
	
	<!-- Brand / Logo Area -->
	<div class="h-20 flex items-center gap-3 px-6 border-b border-[#EADBCC]">
		<div class="w-10 h-10 rounded-xl bg-gradient-maroon text-white flex items-center justify-center text-xl font-bold shadow-sm flex-shrink-0">
			ت
		</div>
		<div class="min-w-0">
			<h1 class="font-bold text-sm text-[#18181B] leading-tight truncate">
				{{ settings('site_name', 'ألبان وسوبرماركت التوحيد') }}
			</h1>
			<span class="text-[11px] text-[#71717A] block font-medium">
				{{ __('لوحة إدارة السوبرماركت والفروع') }}
			</span>
		</div>
	</div>

	@php
		$pendingJobsCount = \App\Models\JobApplication::where('status', 'pending')->count();
		$unreadMsgsCount = \App\Models\ContactMessage::where('is_read', false)->count();
	@endphp

	<!-- Navigation Links (Clean Independent Scroll without truncating) -->
	<nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1.5 custom-scrollbar">
		
		<!-- Dashboard -->
		<a href="{{ route('admin.dashboard') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">📊</span>
			<span class="whitespace-nowrap">{{ __('الرئيسية والإحصائيات') }}</span>
		</a>

		<!-- Reports -->
		<a href="{{ route('admin.reports.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">📈</span>
			<span class="whitespace-nowrap">{{ __('التقارير الشاملة والمبيعات') }}</span>
		</a>

		<!-- Branches -->
		<a href="{{ route('admin.branches.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.branches.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">🏪</span>
			<span class="whitespace-nowrap">{{ __('فروع التوحيد (4 فروع)') }}</span>
		</a>

		<!-- Orders -->
		<a href="{{ route('admin.orders.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">🛍️</span>
			<span class="whitespace-nowrap">{{ __('إدارة الطلبات والتوصيل') }}</span>
		</a>

		<!-- Products -->
		<a href="{{ route('admin.products.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">🧀</span>
			<span class="whitespace-nowrap">{{ __('المنتجات والمخزون') }}</span>
		</a>

		<!-- Categories -->
		<a href="{{ route('admin.categories.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">📑</span>
			<span class="whitespace-nowrap">{{ __('التصنيفات والأقسام') }}</span>
		</a>

		<!-- Weekly Offers Magazines (Phase 7 Unified) -->
		<a href="{{ route('admin.magazines.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.magazines.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-red-700 hover:text-red-900 hover:bg-red-50/50 bg-red-50/30 border border-red-100/60' }}">
			<span class="text-base flex-shrink-0">📰</span>
			<span class="whitespace-nowrap">{{ __('مجلات العروض الأسبوعية') }}</span>
		</a>

		<!-- Bundles -->
		<a href="{{ route('admin.bundles.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.bundles.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">📦</span>
			<span class="whitespace-nowrap">{{ __('العروض المجمعة (Bundles)') }}</span>
		</a>

		<!-- Deals -->
		<a href="{{ route('admin.deals.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.deals.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">⚡</span>
			<span class="whitespace-nowrap">{{ __('التخفيضات والصفقات') }}</span>
		</a>

		<!-- Banners -->
		<a href="{{ route('admin.banners.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">🖼️</span>
			<span class="whitespace-nowrap">{{ __('البانرات والإعلانات') }}</span>
		</a>

		<!-- Job Applications -->
		<a href="{{ route('admin.job-applications.index') }}" 
		   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.job-applications.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<div class="flex items-center gap-3">
				<span class="text-base flex-shrink-0">💼</span>
				<span class="whitespace-nowrap">{{ __('طلبات التوظيف') }}</span>
			</div>
			@if($pendingJobsCount > 0)
				<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">
					{{ $pendingJobsCount }}
				</span>
			@endif
		</a>

		<!-- Contact Messages -->
		<a href="{{ route('admin.contact-messages.index') }}" 
		   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.contact-messages.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<div class="flex items-center gap-3">
				<span class="text-base flex-shrink-0">✉️</span>
				<span class="whitespace-nowrap">{{ __('رسائل واستفسارات') }}</span>
			</div>
			@if($unreadMsgsCount > 0)
				<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-600 text-white">
					{{ $unreadMsgsCount }}
				</span>
			@endif
		</a>

		<!-- Notifications Center -->
		<a href="{{ route('admin.notifications.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">🔔</span>
			<span class="whitespace-nowrap">{{ __('مركز وتاريخ الإشعارات') }}</span>
		</a>

		<!-- Divider -->
		<div class="pt-2 pb-1">
			<div class="border-t border-[#EADBCC]"></div>
		</div>

		<!-- Users -->
		<a href="{{ route('admin.users.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">👥</span>
			<span class="whitespace-nowrap">{{ __('المستخدمين والأدوار') }}</span>
		</a>

		<!-- Settings -->
		<a href="{{ route('admin.settings.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<span class="text-base flex-shrink-0">⚙️</span>
			<span class="whitespace-nowrap">{{ __('إعدادات المتجر') }}</span>
		</a>

		<!-- View Storefront Button -->
		<div class="pt-3">
			<a href="{{ route('site.home') }}" target="_blank" 
			   class="flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-[#F8F6F2] hover:bg-[#EADBCC] text-[#18181B] text-xs font-bold border border-[#EADBCC] transition-all">
				<span>👁️</span>
				<span>{{ __('معاينة المتجر') }}</span>
			</a>
		</div>

	</nav>

	<!-- Bottom Profile & Logout -->
	<div class="p-4 border-t border-[#EADBCC] bg-[#F8F6F2]/50">
		<div class="flex items-center gap-3">
			<div class="w-9 h-9 rounded-full bg-[#18181B] text-[#C5A059] flex items-center justify-center font-bold text-xs flex-shrink-0">
				{{ substr(auth()->user()->name ?? 'A', 0, 1) }}
			</div>
			<div class="min-w-0 flex-1">
				<p class="text-xs font-bold text-[#18181B] truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
				<span class="text-[10px] text-[#C5A059] font-medium block truncate">
					{{ auth()->user()->roles->first()?->display_name ?? 'المدير العام' }}
				</span>
			</div>
			<form action="{{ route('logout') }}" method="POST">
				@csrf
				<button type="submit" class="p-1.5 text-[#71717A] hover:text-red-600 transition-colors" title="{{ __('تسجيل الخروج') }}">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
				</button>
			</form>
		</div>
	</div>

</aside>