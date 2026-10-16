@extends('layouts.admin')

@section('title', 'خصائص ومقاسات المنتجات')

@section('content')
<div class="space-y-6" x-data="{ addAttrModal: false }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </span>
                <h1 class="text-xl font-bold text-espresso">خصائص ومقاسات الأزياء (Attributes)</h1>
            </div>
            <p class="text-xs text-mocha mt-1">إدارة أبعاد الموضة: المقاسات، درجات الألوان، نوع الأقمشة، وقَصّات الملابس</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="addAttrModal = true" 
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة خاصية جديدة
            </button>
        </div>
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

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Attributes Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($attributes as $attribute)
            <div class="bg-cream border border-sand/40 rounded-2xl p-5 shadow-sm space-y-4">
                {{-- Card Header --}}
                <div class="flex items-start justify-between border-b border-sand/30 pb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-espresso">{{ $attribute->name }}</h2>
                            <span class="font-mono text-[10px] px-2 py-0.5 rounded-md bg-vanilla text-taupe border border-sand/50 dir-ltr">
                                {{ $attribute->code }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            @if($attribute->type === 'color')
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-clay bg-clay/10 px-2 py-0.5 rounded-full">
                                    <span class="w-2 h-2 rounded-full bg-clay"></span>
                                    منتقي ألوان (Color Palette)
                                </span>
                            @elseif($attribute->type === 'button')
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-action bg-action/10 px-2 py-0.5 rounded-full">
                                    أزرار مقاسات (Size Matrix)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-mocha bg-linen px-2 py-0.5 rounded-full">
                                    قائمة اختيار (Dropdown)
                                </span>
                            @endif

                            @if($attribute->is_filterable)
                                <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    فلتر في المتجر
                                </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" onsubmit="return confirm('حذف الخاصية سيؤدي لحذف كافة قيمها؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-50 text-rose-400 hover:text-rose-600 transition-all cursor-pointer" title="حذف الخاصية">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Values Display --}}
                <div class="space-y-2">
                    <span class="text-[11px] font-bold text-mocha block">القيم المعرفة ({{ $attribute->values->count() }}):</span>
                    
                    <div class="flex flex-wrap gap-2 min-h-[48px] items-center p-3 rounded-xl bg-vanilla/40 border border-sand/30">
                        @forelse($attribute->values as $val)
                            <div class="group relative inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cream border border-sand/60 shadow-2xs hover:border-action transition-all">
                                @if($attribute->type === 'color' && $val->color_code)
                                    <span class="w-3.5 h-3.5 rounded-full border border-black/10 shrink-0 shadow-xs" style="background-color: {{ $val->color_code }};"></span>
                                @endif
                                <span class="text-xs font-semibold text-espresso">{{ $val->value }}</span>
                                
                                <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" onsubmit="return confirm('حذف هذه القيمة؟');" class="inline-block mr-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-taupe hover:text-rose-600 transition-colors cursor-pointer text-xs leading-none">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @empty
                            <span class="text-xs text-taupe italic">لم تتم إضافة أي قيم لهذه الخاصية بعد.</span>
                        @endforelse
                    </div>
                </div>

                {{-- Add Value Form --}}
                <form action="{{ route('admin.attributes.values.store') }}" method="POST" class="pt-2 border-t border-sand/30 flex items-center gap-2" x-data="{ colorHex: '#2B2118' }">
                    @csrf
                    <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                    
                    <div class="flex-1">
                        <input type="text" 
                               name="value" 
                               placeholder="أدخل قيمة جديدة (مثل: {{ $attribute->type === 'color' ? 'كحلي داكن' : ($attribute->type === 'button' ? 'XXL' : 'حرير طبيعي') }})..."
                               class="w-full px-3 py-1.5 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso placeholder-taupe focus:outline-none focus:border-action transition-all"
                               required>
                    </div>

                    @if($attribute->type === 'color')
                        <div class="flex items-center gap-1 bg-vanilla/50 px-2 py-1 rounded-xl border border-sand/60 shrink-0">
                            <input type="color" 
                                   name="color_code" 
                                   x-model="colorHex"
                                   class="w-6 h-6 rounded-md border-0 p-0 cursor-pointer bg-transparent">
                            <span class="font-mono text-[10px] text-mocha" x-text="colorHex"></span>
                        </div>
                    @endif

                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold transition-all shrink-0 cursor-pointer">
                        + إضافة
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-2 py-12 text-center text-taupe bg-cream border border-sand/40 rounded-2xl">
                <p class="text-sm font-semibold text-espresso">لا توجد خصائص مضافة</p>
                <p class="text-xs text-mocha mt-1">أضف المقاسات والألوان والخامات للبدء في توليد تنويعات المنتجات.</p>
            </div>
        @endforelse
    </div>

    {{-- Create Attribute Modal --}}
    <div x-show="addAttrModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/40 flex items-center justify-center p-4">
        <div @click.away="addAttrModal = false" class="bg-cream border border-sand/60 rounded-2xl max-w-md w-full p-6 shadow-xl space-y-5">
            <div class="flex items-center justify-between border-b border-sand/40 pb-3">
                <h3 class="text-sm font-bold text-espresso">إضافة بعد / خاصية جديدة للكتالوج</h3>
                <button @click="addAttrModal = false" class="text-taupe hover:text-espresso text-base cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.attributes.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">اسم الخاصية <span class="text-clay">*</span></label>
                    <input type="text" name="name" placeholder="مثال: طول الفستان أو مقاس الأحذية" class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">كود الخاصية (باللغة الإنجليزية) <span class="text-clay">*</span></label>
                    <input type="text" name="code" placeholder="shoe_size" class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action font-mono dir-ltr text-right" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">طريقة العرض في المتجر</label>
                    <select name="type" class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action">
                        <option value="button">أزرار مقاسات سريعة (Button Matrix)</option>
                        <option value="color">دائرة ألوان كودية (Color Swatch)</option>
                        <option value="select">قائمة منسدلة قياسية (Dropdown)</option>
                        <option value="text">نص وصفي (Text)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_filterable" name="is_filterable" value="1" checked class="w-4 h-4 rounded text-action border-sand/80 focus:ring-action">
                    <label for="is_filterable" class="text-xs font-bold text-espresso cursor-pointer">
                        تفعيل كفلتر تصفية في واجهة المتجر
                    </label>
                </div>

                <div class="pt-4 border-t border-sand/40 flex items-center justify-end gap-2">
                    <button type="button" @click="addAttrModal = false" class="px-4 py-2 rounded-xl bg-linen text-espresso text-xs font-semibold hover:bg-beige cursor-pointer">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-bold cursor-pointer">
                        حفظ الخاصية
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
