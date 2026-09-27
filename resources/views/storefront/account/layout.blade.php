{{-- resources/views/storefront/account/layout.blade.php --}}
@extends('layouts.storefront')

@section('content')
<div class="py-8 bg-canvas-base min-h-screen">
 <div class="container mx-auto px-4 space-y-6">
 
 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">حسابي</span>
 <span>/</span>
 <span class="text-primary-800">@yield('page_title', 'الملف الشخصي')</span>
 </nav>

 <!-- Main Account Grid -->
 <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
 
 <!-- Sidebar Navigation -->
 <aside class="lg:col-span-4 space-y-4">
 <div class="bg-white rounded-3xl p-6 border border-cream shadow-sm space-y-5">
 
 <!-- User Avatar & Quick Info -->
 <div class="flex items-center gap-3.5 border-b border-cream pb-4">
 <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-800 text-lg font-black flex items-center justify-center border border-primary-200 shrink-0">
 {{ mb_substr(auth()->user()->name, 0, 1) }}
 </div>
 <div class="min-w-0">
 <h2 class="font-bold text-sm text-charcoal-heading truncate">{{ auth()->user()->name }}</h2>
 <p class="text-xs text-charcoal-muted font-mono truncate" dir="ltr">{{ auth()->user()->phone }}</p>
 </div>
 </div>

 <!-- Nav Links -->
 <nav class="space-y-1.5 text-xs font-bold">
 <a href="{{ route('account.profile') }}" 
 class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('account.profile') ? 'bg-primary-800 text-white shadow-sm' : 'text-charcoal hover:bg-canvas-base' }}">
 <span class="text-base"></span>
 <span>البيانات الشخصية</span>
 </a>

 <a href="{{ route('account.addresses') }}" 
 class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('account.addresses') ? 'bg-primary-800 text-white shadow-sm' : 'text-charcoal hover:bg-canvas-base' }}">
 <span class="text-base"></span>
 <span>العناوين المحفوظة</span>
 </a>

 <a href="{{ route('account.orders') }}" 
 class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('account.orders*') ? 'bg-primary-800 text-white shadow-sm' : 'text-charcoal hover:bg-canvas-base' }}">
 <span class="text-base"></span>
 <span>سجل طلباتي</span>
 </a>

 <div class="pt-2 border-t border-cream">
 <form action="{{ route('logout') }}" method="POST">
 @csrf
 <button type="submit" 
 class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-rose-600 hover:bg-rose-50 transition-colors text-right cursor-pointer">
 <span class="text-base"></span>
 <span>تسجيل الخروج</span>
 </button>
 </form>
 </div>
 </nav>

 </div>
 </aside>

 <!-- Main Content Area -->
 <div class="lg:col-span-8">
 @yield('account_content')
 </div>

 </div>

 </div>
</div>
@endsection
