<!-- Luxury Admin Sidebar (Pure Tailwind CSS) -->
<aside class="w-72 flex-shrink-0 bg-white border-e border-[#EADBCC] flex flex-col h-screen sticky top-0 z-30 shadow-[2px_0_15px_rgba(0,0,0,0.02)]">
	
	<!-- Brand / Logo Area -->
	<div class="h-20 flex items-center gap-3 px-6 border-b border-[#EADBCC]">
		<div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#382416] to-[#5a3b25] text-[#CBA367] flex items-center justify-center text-xl font-bold shadow-sm flex-shrink-0">
			{{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
		</div>
		<div class="min-w-0">
			<h1 class="font-bold text-sm text-[#18181B] leading-tight truncate">
				{{ settings('site_name', 'أزياء لافيستا') }}
			</h1>
			<span class="text-[11px] text-[#71717A] block font-medium">
				{{ __('لوحة إدارة البوتيك والأزياء') }}
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
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
			<span class="whitespace-nowrap">{{ __('الرئيسية والإحصائيات') }}</span>
		</a>

		<!-- Reports -->
		<a href="{{ route('admin.reports.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
			<span class="whitespace-nowrap">{{ __('التقارير الشاملة والمبيعات') }}</span>
		</a>

		<!-- Branches -->
		<a href="{{ route('admin.branches.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.branches.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
			<span class="whitespace-nowrap">{{ __('فروع ومتاجر') }} {{ settings('site_name', 'أزياء لافيستا') }}</span>
		</a>

		<!-- Orders -->
		<a href="{{ route('admin.orders.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
			<span class="whitespace-nowrap">{{ __('إدارة الطلبات والتوصيل') }}</span>
		</a>

		<!-- Products -->
		<a href="{{ route('admin.products.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
			<span class="whitespace-nowrap">{{ __('الأزياء والموديلات') }}</span>
		</a>

		<!-- Attributes (Sizes & Colors) -->
		<a href="{{ route('admin.attributes.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.attributes.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
			<span class="whitespace-nowrap">{{ __('المقاسات والألوان والخصائص') }}</span>
		</a>

		<!-- Categories -->
		<a href="{{ route('admin.categories.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
			<span class="whitespace-nowrap">{{ __('التصنيفات وأقسام الموضة') }}</span>
		</a>

		<!-- Weekly Offers Magazines (Phase 7 Unified) -->
		<a href="{{ route('admin.magazines.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.magazines.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#A9552F] hover:text-[#382416] hover:bg-[#F3E9D2] bg-[#FBF6EA] border border-[#EADFC8]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
			<span class="whitespace-nowrap">{{ __('كتالوجات ومجلات الموضة') }}</span>
		</a>

		<!-- Bundles -->
		<a href="{{ route('admin.bundles.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.bundles.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
			<span class="whitespace-nowrap">{{ __('الأطقم والتنسيقات (Outfits)') }}</span>
		</a>

		<!-- Deals -->
		<a href="{{ route('admin.deals.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.deals.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
			<span class="whitespace-nowrap">{{ __('التخفيضات والصفقات') }}</span>
		</a>

		<!-- Banners -->
		<a href="{{ route('admin.banners.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
			<span class="whitespace-nowrap">{{ __('البانرات والكولكشنات') }}</span>
		</a>

		<!-- Job Applications -->
		<a href="{{ route('admin.job-applications.index') }}" 
		   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.job-applications.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<div class="flex items-center gap-3">
				<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
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
				<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
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
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
			<span class="whitespace-nowrap">{{ __('مركز وتاريخ الإشعارات') }}</span>
		</a>

		<!-- Divider -->
		<div class="pt-2 pb-1">
			<div class="border-t border-[#EADBCC]"></div>
		</div>

		<!-- Users -->
		<a href="{{ route('admin.users.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
			<span class="whitespace-nowrap">{{ __('المستخدمين والأدوار') }}</span>
		</a>

		<!-- Settings -->
		<a href="{{ route('admin.settings.index') }}" 
		   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#C5A059] text-white shadow-sm' : 'text-[#52525B] hover:text-[#18181B] hover:bg-[#F8F6F2]' }}">
			<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
			<span class="whitespace-nowrap">{{ __('إعدادات المتجر') }}</span>
		</a>

		<!-- View Storefront Button -->
		<div class="pt-3">
			<a href="{{ route('site.home') }}" target="_blank" 
			   class="flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-[#F8F6F2] hover:bg-[#EADBCC] text-[#18181B] text-xs font-bold border border-[#EADBCC] transition-all">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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