@extends('layouts.admin')

@section('title', 'فروع وصالات عرض الأزياء')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">الفروع وصالات العرض</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">فروع ومنافذ البيع وصالات العرض</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">إدارة صالات العرض الفعلية، عناوين البوتيكات، أرقام التواصل، ومواعيد العمل</p>
        </div>
        <a href="{{ route('admin.branches.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>إضافة فرع / صالة عرض</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Branches Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-[#6B5642] font-bold">
                        <th class="py-3.5 px-5" style="width: 60px;">#</th>
                        <th class="py-3.5 px-4">الصورة</th>
                        <th class="py-3.5 px-5">اسم الفرع / البوتيك</th>
                        <th class="py-3.5 px-5">العنوان وموقع الخريطة</th>
                        <th class="py-3.5 px-4">أرقام التواصل</th>
                        <th class="py-3.5 px-4">مواعيد العمل</th>
                        <th class="py-3.5 px-4 text-center">الحالة</th>
                        <th class="py-3.5 px-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                    @forelse($branches as $branch)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-5 font-mono text-xs text-[#8F7B63]">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-12 h-12 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] p-0.5 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($branch->image_url)
                                        <img src="{{ $branch->image_url }}" alt="{{ $branch->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-xl">🏛️</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-bold text-xs text-[#2B2118] block">{{ $branch->name }}</span>
                                <span class="text-[10px] text-[#8F7B63]">{{ $branch->slug }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-[#6B5642] max-w-xs">
                                <span class="block leading-relaxed">{{ $branch->address }}</span>
                                @if($branch->google_maps_url)
                                    <a href="{{ $branch->google_maps_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-[#A9552F] hover:underline mt-1 font-medium">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>عرض الموقع على الخريطة</span>
                                    </a>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#2B2118]">
                                <a href="tel:{{ $branch->phone }}" class="dir-ltr text-right font-medium block hover:text-[#A9552F] transition-colors">
                                    {{ $branch->phone }}
                                </a>
                                @if($branch->whatsapp)
                                    <span class="text-[10px] text-emerald-700 block mt-0.5">واتساب: {{ $branch->whatsapp }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[11px] text-[#6B5642] max-w-xs">
                                <span class="block">{{ $branch->opening_hours ?? 'يومياً 10 ص - 11 م' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($branch->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        مفتوح ومفعل
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F3E9D2] text-[#8F7B63] border border-[#EADFC8]">
                                        مغلق مؤقتاً
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.branches.edit', $branch->id) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا الفرع؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-[#A9552F] text-xs font-medium transition-colors">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-sm text-[#8F7B63]">
                                لا توجد فروع أو صالات عرض مسجلة حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($branches->hasPages())
            <div class="p-4 border-t border-[#EADFC8] bg-[#FBF6EA]/50">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
