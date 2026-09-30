@extends('layouts.admin')

@section('title', __('إدارة المشرفين والمستخدمين'))

@section('content')
<div class="space-y-6">
 {{-- Header --}}
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <h2 class="text-2xl font-bold text-[#1F2937] tracking-tight">{{ __('إدارة المشرفين والمستخدمين') }}</h2>
 <p class="text-sm text-gray-500 mt-0.5">{{ __('إدارة حسابات المسؤولين والصلاحيات والوصول للوحة التحكم') }}</p>
 </div>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-2 shrink-0 cursor-pointer">
            <span>+</span>
            <span>{{ __('إنشاء مشرف جديد') }}</span>
        </a>
    </div>

 {{-- Main Table Card --}}
 <div class="bg-white border border-[border-orange-100] rounded-2xl shadow-sm overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full text-right border-collapse">
 <thead>
 <tr class="border-b border-[border-orange-100] bg-[bg-canvas-base]/70 text-gray-500 text-xs uppercase font-semibold">
 <th class="py-3.5 px-5">#</th>
 <th class="py-3.5 px-5">{{ __('اسم المشرف') }}</th>
 <th class="py-3.5 px-5">{{ __('البريد الإلكتروني') }}</th>
 <th class="py-3.5 px-5">{{ __('الدور / الصلاحية') }}</th>
 <th class="py-3.5 px-5 text-center">{{ __('العمليات') }}</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-[#F0ECE1] text-sm text-[#1F2937]">
 @forelse($users as $user)
 <tr class="hover:bg-[bg-canvas-base]/40 transition-colors">
 <td class="py-4 px-5 text-gray-500 font-mono text-xs">{{ $loop->iteration }}</td>
 <td class="py-4 px-5">
 <div class="font-bold text-[#1F2937] flex items-center gap-2">
 <div class="w-8 h-8 rounded-full bg-[#1F2937] text-[#DC2626] flex items-center justify-center text-xs font-bold uppercase">
 {{ mb_substr($user->name, 0, 1) }}
 </div>
 <span>{{ $user->name }}</span>
 </div>
 </td>
 <td class="py-4 px-5 text-gray-500 font-mono text-xs">
 {{ $user->email }}
 </td>
 <td class="py-4 px-5">
 @if(method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty())
 @foreach($user->getRoleNames() as $roleName)
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[bg-canvas-base] text-[#DC2626] border border-[border-orange-100]">
 {{ $roleName }}
 </span>
 @endforeach
 @else
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
 مسؤول
 </span>
 @endif
 </td>
 <td class="py-4 px-5 text-center">
 <div class="flex items-center justify-center gap-2">
 <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 rounded-lg border border-[border-orange-100] bg-[bg-canvas-base] hover:bg-[#EDE8DC] text-[#1F2937] font-semibold text-xs transition-colors">
 {{ __('تعديل') }}
 </a>
 @if(auth()->id() !== $user->id)
 <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" data-confirm-message="{{ __('هل أنت متأكد من رغبتك في حذف هذا المشرف؟') }}">
 @csrf
 @method('DELETE')
 <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs transition-colors">
 {{ __('حذف') }}
 </button>
 </form>
 @endif
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="5" class="py-12 text-center text-gray-500">
 {{ __('لا يوجد مشرفين مسجلين حالياً.') }}
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if(method_exists($users, 'hasPages') && $users->hasPages())
 <div class="p-4 border-t border-[border-orange-100] bg-[bg-canvas-base]/30">
 {{ $users->links() }}
 </div>
 @endif
 </div>
</div>
@endsection
