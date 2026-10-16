@extends('layouts.admin')

@section('title', 'دليل العملاء والمشترين')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold text-clay uppercase tracking-wider">قاعدة بيانات المتجر</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                دليل العملاء والمشترين
            </h1>
            <p class="text-xs text-mocha mt-1">
                إدارة ملفات العملاء المسجلين، أرقام الهواتف، العناوين، وسجلات طلباتهم السابقة.
            </p>
        </div>

        <a href="{{ route('admin.clients.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span>إضافة عميل جديد</span>
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Filter & Search Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.clients.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-96">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم، البريد الإلكتروني، أو رقم الهاتف..." 
                       class="w-full pl-3 pr-9 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso placeholder-taupe focus:outline-none focus:border-action transition-all">
                <svg class="w-4 h-4 text-taupe absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                @if(request('search'))
                    <a href="{{ route('admin.clients.index') }}" class="px-3 py-2 text-xs text-mocha hover:text-espresso underline">إلغاء البحث</a>
                @endif
                <button type="submit" class="px-5 py-2 bg-linen hover:bg-beige text-espresso text-xs font-semibold rounded-xl border border-sand/60 transition-all cursor-pointer">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-linen/60 text-mocha font-bold border-b border-sand/40 text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-right">العميل</th>
                        <th class="py-3 px-4 text-right">البريد الإلكتروني</th>
                        <th class="py-3 px-4 text-right">رقم الهاتف</th>
                        <th class="py-3 px-4 text-right">عنوان التوصيل</th>
                        <th class="py-3 px-4 text-center">الطلبات</th>
                        <th class="py-3 px-4 text-center">تاريخ التسجيل</th>
                        <th class="py-3 px-4 text-left">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/30">
                    @forelse($clients as $client)
                        <tr class="hover:bg-vanilla/30 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-espresso">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-vanilla border border-sand/60 flex items-center justify-center font-bold text-taupe text-xs shrink-0">
                                        {{ mb_substr($client->name, 0, 1) }}
                                    </div>
                                    <span>{{ $client->name }}</span>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-[11px] text-mocha dir-ltr text-right">
                                {{ $client->email }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-xs text-espresso dir-ltr text-right">
                                {{ $client->phone }}
                            </td>

                            <td class="py-3.5 px-4 text-mocha max-w-xs truncate">
                                {{ $client->address }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-linen text-mocha border border-sand/40">
                                    {{ $client->orders_count }} طلب
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono text-[11px] text-taupe">
                                {{ $client->created_at ? $client->created_at->format('Y-m-d') : '—' }}
                            </td>

                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.clients.edit', $client->id) }}" 
                                       class="p-1.5 rounded-lg hover:bg-beige text-mocha hover:text-espresso transition-all" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-50 text-rose-500 hover:text-rose-700 transition-all cursor-pointer" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-taupe">
                                لا يوجد عملاء مسجلين حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="p-4 border-t border-sand/40 bg-linen/20">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
