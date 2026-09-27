<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم') | {{ settings('site_name', 'ألبان وسوبرماركت التوحيد') }}</title>

    @php
        $siteFavicon = get_site_favicon();
    @endphp
    @if($siteFavicon)
        <link rel="icon" href="{{ $siteFavicon }}">
        <link rel="shortcut icon" href="{{ $siteFavicon }}">
        <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🥛</text></svg>">
    @endif

    <!-- Google Arabic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & App Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
        body {
            font-family: 'Cairo', 'Alexandria', sans-serif;
        }
        .admin-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .admin-scrollbar::-webkit-scrollbar-thumb {
            background: #FEE2E2;
            border-radius: 4px;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-canvas-base text-charcoal antialiased overflow-hidden"
      x-data="{
          sidebarCollapsed: localStorage.getItem('tawheed_admin_sidebar_collapsed') === 'true',
          mobileSidebarOpen: false,
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('tawheed_admin_sidebar_collapsed', this.sidebarCollapsed);
          }
      }">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="mobileSidebarOpen" 
         x-cloak
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs lg:hidden"></div>

    <!-- Master Layout Wrapper -->
    <div class="flex h-screen w-screen overflow-hidden">
        
        <!-- 1. Collapsible & Responsive Sidebar -->
        <aside :class="{
                   'w-72': !sidebarCollapsed,
                   'w-20': sidebarCollapsed,
                   'translate-x-0': mobileSidebarOpen,
                   'max-lg:rtl:translate-x-full max-lg:ltr:-translate-x-full': !mobileSidebarOpen
               }"
               class="fixed lg:static inset-y-0 start-0 flex-shrink-0 bg-white border-e border-orange-100 flex flex-col h-full z-50 lg:z-30 shadow-sm transition-all duration-300 ease-in-out">
            
            <!-- Brand & Sidebar Toggle -->
            <div class="h-20 flex items-center justify-between px-4 sm:px-6 border-b border-orange-100 bg-canvas-base/50">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-700 via-red-600 to-orange-500 text-white flex items-center justify-center font-black text-xl shadow-md shrink-0 border border-orange-200">
                        ت
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <h1 class="font-black text-sm text-red-600 leading-tight truncate">
                                ألبان التوحيد
                            </h1>
                            <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-orange-500 text-white uppercase tracking-wider">ماركت</span>
                        </div>
                        <span class="text-[10px] text-gray-500 block truncate font-bold">لوحة الإدارة والتحكم</span>
                    </div>
                </div>

                <!-- Desktop Collapse Button inside Sidebar Header -->
                <button type="button" 
                        @click="toggleSidebar()" 
                        class="hidden lg:flex w-8 h-8 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 items-center justify-center transition-colors shrink-0 cursor-pointer"
                        :title="sidebarCollapsed ? 'توسيع القائمة' : 'تصغير القائمة'">
                    <svg class="w-4 h-4 transition-transform duration-300 rtl:rotate-180" 
                         :class="{ 'rotate-180 rtl:rotate-0': sidebarCollapsed }" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Mobile Close Button -->
                <button type="button" 
                        @click="mobileSidebarOpen = false" 
                        class="flex lg:hidden w-8 h-8 rounded-lg bg-gray-100 text-gray-600 items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation Menu -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 admin-scrollbar">
                
                {{-- 1. Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="الرئيسية والإحصائيات">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">الرئيسية والإحصائيات</span>
                </a>

                {{-- 2. Orders --}}
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.orders.*') || request()->routeIs('orders.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="إدارة الطلبات والتوصيل">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">طلبات العامرية والتوصيل</span>
                </a>

                {{-- 3. Products & Portions --}}
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="المنتجات والأوزان">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">المنتجات والأوزان والأسعار</span>
                </a>

                {{-- 4. Categories --}}
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="أقسام السوبرماركت">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">أقسام السوبرماركت</span>
                </a>

                {{-- 5. Branches (4 Ameriya Branches) --}}
                <a href="{{ route('admin.branches.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.branches.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="فروع العامرية">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">فروع العامرية (4 فروع)</span>
                </a>

                {{-- 6. Weekly Offers Magazine Editions (Phase 7) --}}
                <a href="{{ route('admin.magazines.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.magazines.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-red-700 hover:bg-red-50 font-black' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="مجلات العروض الأسبوعية">
                    <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">مجلات العروض الأسبوعية</span>
                </a>

                {{-- 7. Deals & Discounts (مجلة العروض) --}}
                <a href="{{ route('admin.deals.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.deals.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="تخفيضات ومجلة العروض">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">مجلة العروض والتخفيضات</span>
                </a>

                {{-- 7. Bundles --}}
                <a href="{{ route('admin.bundles.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.bundles.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="عروض التوفير المجمعة (Bundles)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">عروض التوفير المجمعة (Bundles)</span>
                </a>

                {{-- 8. Comprehensive Reports & Performance --}}
                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="التقارير الشاملة">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">التقارير الشاملة والمبيعات</span>
                </a>

                {{-- 9. Banners --}}
                <a href="{{ route('admin.banners.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="بانرات الواجهة">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">بانرات الواجهة الرئيسية</span>
                </a>

                {{-- 12. Job Applications --}}
                <a href="{{ route('admin.job-applications.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.job-applications.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="طلبات التوظيف">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">طلبات التوظيف والوظائف</span>
                </a>

                {{-- 13. Contact Messages --}}
                <a href="{{ route('admin.contact-messages.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="رسائل واستفسارات العملاء">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">رسائل واستفسارات العملاء</span>
                </a>

                {{-- 14. Notifications Center --}}
                <a href="{{ route('admin.notifications.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="مركز وتاريخ الإشعارات">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">مركز وتاريخ الإشعارات</span>
                </a>

                <div class="border-t border-orange-100 my-2"></div>

                {{-- 15. Users --}}
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="المستخدمين والمشرفين">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">المستخدمين والمشرفين</span>
                </a>

                {{-- 15. Settings --}}
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : ''"
                   title="إعدادات المتجر">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">إعدادات المتجر العامة</span>
                </a>

                {{-- Live Store Preview --}}
                <div class="pt-2">
                    <a href="{{ route('site.home') }}" 
                       target="_blank" 
                       class="flex items-center gap-2 w-full py-2.5 px-3 rounded-xl bg-orange-50 hover:bg-orange-100 text-red-700 text-xs font-bold border border-orange-200 transition-all justify-center"
                       title="معاينة المتجر المباشر">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate">معاينة المتجر</span>
                    </a>
                </div>

            </nav>

            <!-- Bottom Profile / Logout -->
            <div class="p-3 sm:p-4 border-t border-orange-100 bg-canvas-base/60">
                <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-8 h-8 rounded-xl bg-red-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-sm">
                        {{ substr(auth()->user()->name ?? 'T', 0, 1) }}
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-charcoal truncate">{{ auth()->user()->name ?? 'مدير التوحيد' }}</p>
                        <span class="text-[10px] text-orange-600 block truncate font-medium">مشرف عام المتجر</span>
                    </div>
                    <form x-show="!sidebarCollapsed" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 p-1 cursor-pointer" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- 2. Main Content Viewport -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto bg-[#FDFCFB]">
            
            <!-- Sticky Top Header -->
            <header class="h-20 px-4 sm:px-8 bg-white/95 backdrop-blur-md border-b border-orange-100 flex items-center justify-between sticky top-0 z-20 shrink-0">
                
                <!-- Left: Sidebar Toggle Button & Search -->
                <div class="flex items-center gap-3 sm:gap-4 flex-1 max-w-xl">
                    <button type="button" 
                            @click="if (window.innerWidth >= 1024) { toggleSidebar(); } else { mobileSidebarOpen = !mobileSidebarOpen; }"
                            class="w-10 h-10 rounded-xl border border-orange-200 bg-orange-50/50 hover:bg-orange-100 text-red-600 flex items-center justify-center transition-all shadow-xs shrink-0 cursor-pointer"
                            title="تبديل إظهار / إخفاء القائمة الجانبية">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="relative w-full max-w-md hidden sm:block">
                        <input type="text" 
                               placeholder="ابحث عن منتجات، طلبات، فروع، عملاء..." 
                               class="w-full ps-10 pe-4 py-2.5 text-xs bg-canvas-base border border-orange-100 rounded-xl text-charcoal placeholder-gray-400 focus:outline-none focus:border-red-500 focus:bg-white transition-all">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Right: Quick actions & Real-time Notifications -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    
                    <!-- Real-time AJAX Notification Bell -->
                    <x-admin.notification-bell />

                    <!-- Quick Create Universal Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" 
                                @click="open = !open" 
                                class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-45': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">إنشاء جديد</span>
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute end-0 mt-2 w-64 bg-white border border-orange-100 rounded-2xl shadow-xl py-2 z-50 text-xs font-bold divide-y divide-orange-50">
                            
                            <div class="px-3 py-1.5 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                اختصارات الإنشاء والإضافة
                            </div>

                            <div class="py-1">
                                <a href="{{ route('admin.orders.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-red-700 bg-red-50/50 hover:bg-red-50 hover:text-red-800 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-red-100 text-red-700 flex items-center justify-center text-xs">📝</span>
                                    <span>تسجيل طلب يدوي (واتساب / فرع)</span>
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xs">🥛</span>
                                    <span>إضافة صنف / منتج جديد</span>
                                </a>
                                <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xs">🏷️</span>
                                    <span>إضافة قسم / تصنيف</span>
                                </a>
                                <a href="{{ route('deals.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">🔥</span>
                                    <span>إضافة عرض وتخفيض</span>
                                </a>
                                <a href="{{ route('admin.bundles.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">📦</span>
                                    <span>إنشاء عرض مجمع (Bundle)</span>
                                </a>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('admin.branches.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">🏪</span>
                                    <span>إضافة فرع جديد</span>
                                </a>
                                <a href="{{ route('banners.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">🖼️</span>
                                    <span>إضافة بانر وإعلان</span>
                                </a>
                                <a href="{{ route('users.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-gray-700 hover:bg-orange-50 hover:text-red-700 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs">👤</span>
                                    <span>إنشاء حساب مشرف</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('site.home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-orange-50 hover:bg-orange-100 border border-orange-200 text-xs font-bold text-red-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>معاينة المتجر</span>
                    </a>

                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-red-700 font-bold text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="hidden md:inline">مدير المتجر</span>
                    </div>
                </div>

            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>

    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-200">
        <div class="relative w-full max-w-md bg-white border border-orange-100 rounded-2xl shadow-2xl p-6 sm:p-7 text-center transform scale-95 transition-all duration-200" id="deleteModalCard">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-3xl shadow-xs">
                <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-charcoal mb-2" id="deleteModalTitle">
                تأكيد عملية الحذف
            </h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-6 leading-relaxed" id="deleteModalMessage">
                هل أنت متأكد من رغبتك في حذف هذا العنصر؟ لن يمكنك التراجع عن هذه العملية لاحقاً.
            </p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-charcoal text-xs sm:text-sm font-bold transition-colors flex-1 cursor-pointer">
                    إلغاء
                </button>
                <button type="button" id="deleteModalConfirmBtn" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-bold shadow-sm transition-all flex-1 cursor-pointer">
                    نعم، احذف
                </button>
            </div>
        </div>
    </div>

    {{-- TOASTER NOTIFICATIONS CONTAINER --}}
    <div id="toastContainer" class="fixed top-6 end-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        @if (session('success'))
            <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-emerald-200 shadow-xl transition-all transform translate-y-0 opacity-100">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-200">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <h5 class="text-xs font-bold text-gray-900">تم بنجاح</h5>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.closest('.toast-item').remove()" class="text-gray-400 hover:text-gray-700 text-xs p-1">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-rose-200 shadow-xl transition-all transform translate-y-0 opacity-100">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0 border border-rose-200">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <h5 class="text-xs font-bold text-rose-700">تنبيه خطأ</h5>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.closest('.toast-item').remove()" class="text-gray-400 hover:text-gray-700 text-xs p-1">✕</button>
            </div>
        @endif
    </div>

    <script>
        let pendingFormToSubmit = null;

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border shadow-xl transition-all duration-300 transform translate-y-2 opacity-0 ${
                type === 'success' ? 'border-emerald-200' : (type === 'error' ? 'border-rose-200' : 'border-orange-100')
            }`;

            const iconBg = type === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : (type === 'error' ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-orange-50 text-orange-600 border-orange-200');
            const title = type === 'success' ? 'تم بنجاح' : (type === 'error' ? 'خطأ' : 'إشعار');

            toast.innerHTML = `
                <div class="w-9 h-9 rounded-xl ${iconBg} flex items-center justify-center text-base font-bold shrink-0 border">
                    ✓
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <h5 class="text-xs font-bold text-gray-900">${title}</h5>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">${message}</p>
                </div>
                <button type="button" onclick="this.closest('.toast-item').remove()" class="text-gray-400 hover:text-gray-700 text-xs p-1">✕</button>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const existingToasts = document.querySelectorAll('.toast-item');
            existingToasts.forEach((toast) => {
                setTimeout(() => {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 4500);
            });
        });

        function openDeleteModal(form, message = null) {
            pendingFormToSubmit = form;
            const modal = document.getElementById('deleteModal');
            const modalMsg = document.getElementById('deleteModalMessage');
            const card = document.getElementById('deleteModalCard');

            if (message && modalMsg) {
                modalMsg.innerText = message;
            } else if (modalMsg) {
                modalMsg.innerText = 'هل أنت متأكد من رغبتك في حذف هذا العنصر؟ لن يمكنك التراجع عن هذه العملية لاحقاً.';
            }

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const card = document.getElementById('deleteModalCard');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            pendingFormToSubmit = null;
        }

        document.getElementById('deleteModalConfirmBtn')?.addEventListener('click', () => {
            if (pendingFormToSubmit) {
                pendingFormToSubmit.dataset.confirmed = "true";
                pendingFormToSubmit.submit();
            }
            closeDeleteModal();
        });

        document.addEventListener('submit', function(e) {
            const form = e.target;
            const isDelete = form.querySelector('input[name="_method"][value="DELETE"]') || form.hasAttribute('data-confirm');
            
            if (!isDelete || form.dataset.confirmed === "true") {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            const customMessage = form.getAttribute('data-confirm-message') || null;
            openDeleteModal(form, customMessage);
        }, true);
    </script>

    @stack('scripts')
</body>
</html>