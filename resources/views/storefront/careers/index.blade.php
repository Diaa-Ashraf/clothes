{{-- resources/views/storefront/careers/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'وظائف ' . settings('site_name', 'أزياء لافيستا') . ' | انضم لفريقنا في عالم الموضة')

@section('content')
<div class="bg-[#FBF6EA] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#2B2118] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">وظائف البوتيك</span>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-[#2B2118] text-[#FBF6EA] p-6 sm:p-12 shadow-xl border border-[#CDB78F]/30">
            <div class="relative z-10 max-w-2xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F3E9D2]/15 text-[#CDB78F] border border-[#CDB78F]/30 text-xs font-bold">
                    <span>بيئة عمل راقية ومحفزة للإبداع</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black leading-tight tracking-tight text-[#FBF6EA]">
                    انضم إلى عائلة {{ settings('site_name', 'أزياء لافيستا') }}
                </h1>
                <p class="text-xs sm:text-sm text-[#EADFC8] leading-relaxed">
                    نبحث دائماً عن الشغوفين بعالم الأزياء والموضة وخدمة العملاء الراقية للانضمام لفريقنا في بوتيكاتنا. نوفر فرص عمل واعدة، رواتب ومكافآت مجزية، وبيئة عمل قائمة على التقدير والتطوير المستمر.
                </p>
                <div class="pt-2">
                    <a href="#apply-form" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#A9552F] hover:bg-[#8e4524] text-white text-xs font-black transition-all shadow-md">
                        <span>قدّم سيرتك الذاتية الآن</span>
                        <span>↓</span>
                    </a>
                </div>
            </div>

            <!-- Background Shapes -->
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-[#A9552F]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-[#CDB78F]/10 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Benefits Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 text-center space-y-2.5 shadow-xs">
                <div class="w-10 h-10 mx-auto rounded-xl bg-[#F3E9D2] text-[#A9552F] flex items-center justify-center font-bold text-lg">
                    💰
                </div>
                <h3 class="text-xs font-bold text-[#2B2118]">رواتب ومكافآت مبيعات</h3>
                <p class="text-[11px] text-[#8F7B63]">مرتبات تنافسية وعمولات مبيعات وحوافز تميز شهرية</p>
            </div>
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 text-center space-y-2.5 shadow-xs">
                <div class="w-10 h-10 mx-auto rounded-xl bg-[#F3E9D2] text-[#A9552F] flex items-center justify-center font-bold text-lg">
                    🛡️
                </div>
                <h3 class="text-xs font-bold text-[#2B2118]">عقود رسمية واستقرار</h3>
                <p class="text-[11px] text-[#8F7B63]">تأمينات وعقود عمل واضحة وتأمينات صحية واجتماعية</p>
            </div>
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 text-center space-y-2.5 shadow-xs">
                <div class="w-10 h-10 mx-auto rounded-xl bg-[#F3E9D2] text-[#A9552F] flex items-center justify-center font-bold text-lg">
                    📈
                </div>
                <h3 class="text-xs font-bold text-[#2B2118]">تطوير في صناعة الأزياء</h3>
                <p class="text-[11px] text-[#8F7B63]">تدريب مستمر على الستايست وفنون التنسيق وإدارة البوتيكات</p>
            </div>
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 text-center space-y-2.5 shadow-xs">
                <div class="w-10 h-10 mx-auto rounded-xl bg-[#F3E9D2] text-[#A9552F] flex items-center justify-center font-bold text-lg">
                    🤝
                </div>
                <h3 class="text-xs font-bold text-[#2B2118]">بيئة عمل راقية ومحترمة</h3>
                <p class="text-[11px] text-[#8F7B63]">فريق عمل متناغم يقدر الذوق الرفيع والعمل الجماعي</p>
            </div>
        </div>

        <!-- Available Roles Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-[#2B2118]">الوظائف المتاحة حالياً في البوتيك</h2>
                    <p class="text-xs text-[#8F7B63]">اختر الوظيفة المناسبة لك واضغط تقديم لتعبئة النموذج المباشر</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($openPositions as $position)
                <div class="bg-white rounded-2xl border border-[#EADFC8] hover:border-[#CDB78F] p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F3E9D2] text-[#6B5642] border border-[#EADFC8]">
                                {{ $position['department'] }}
                            </span>
                            <span class="text-[11px] text-[#8F7B63] font-medium">
                                {{ $position['type'] }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-[#2B2118]">{{ $position['title'] }}</h3>
                        <p class="text-xs text-[#6B5642] leading-relaxed">
                            {{ $position['description'] }}
                        </p>
                    </div>

                    <button type="button" 
                            onclick="selectJobPosition('{{ $position['title'] }}')"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#FBF6EA] hover:bg-[#382416] text-[#2B2118] hover:text-[#FBF6EA] border border-[#EADFC8] hover:border-[#382416] text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>قدّم لهذه الوظيفة</span>
                        <span>←</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Application Form Card -->
        <div id="apply-form" class="bg-white rounded-3xl border border-[#EADFC8] shadow-sm p-6 sm:p-10 max-w-3xl mx-auto space-y-8">
            <div class="text-center space-y-2">
                <span class="inline-block p-3 rounded-2xl bg-[#F3E9D2] text-[#A9552F] text-2xl font-bold">📄</span>
                <h2 class="text-xl sm:text-2xl font-black text-[#2B2118]">نموذج التقديم للوظائف</h2>
                <p class="text-xs text-[#8F7B63]">املأ البيانات بدقة وأرفق سيرتك الذاتية وسيتم مراجعتها من إدارة الموارد البشرية</p>
            </div>

            @if(session('success'))
            <div class="bg-[#F3E9D2] border border-[#CDB78F] rounded-2xl p-4 text-[#2B2118] text-xs flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-[#FBF6EA] border border-[#A9552F] rounded-2xl p-4 space-y-1 text-[#A9552F] text-xs">
                <span class="font-bold block">يرجى تصحيح الأخطاء التالية:</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-[#2B2118]">
                            الاسم بالكامل <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', auth()->user()?->name) }}"
                               required
                               placeholder="مثال: سارة أحمد محمود"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors bg-[#FBF6EA]/30">
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-xs font-bold text-[#2B2118]">
                            رقم الهاتف المحمول (مصر) <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               dir="ltr"
                               value="{{ old('phone', auth()->user()?->phone) }}"
                               required
                               placeholder="01012345678"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] text-right focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors bg-[#FBF6EA]/30">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-[#2B2118]">
                            البريد الإلكتروني <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               dir="ltr"
                               value="{{ old('email', auth()->user()?->email) }}"
                               placeholder="example@mail.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] text-right focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors bg-[#FBF6EA]/30">
                    </div>

                    <!-- Position -->
                    <div class="space-y-1.5">
                        <label for="position" class="block text-xs font-bold text-[#2B2118]">
                            الوظيفة المتقدم إليها <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="text" 
                               name="position" 
                               id="position" 
                               value="{{ old('position') }}"
                               required
                               placeholder="مثال: منسق أزياء ومبيعات"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors bg-[#FBF6EA]/30">
                    </div>
                </div>

                <!-- Preferred Branch -->
                <div class="space-y-1.5">
                    <label for="branch_id" class="block text-xs font-bold text-[#2B2118]">
                        الفرع أو البوتيك المفضل للعمل به <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                    </label>
                    <select name="branch_id" id="branch_id" class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] bg-white focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                        <option value="">أي فرع أو بوتيك متاح</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- CV File Upload -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-[#2B2118]">
                        ملف السيرة الذاتية (CV) <span class="text-[#A9552F]">*</span>
                    </label>
                    <div class="border-2 border-dashed border-[#EADFC8] hover:border-[#382416] rounded-2xl p-6 text-center transition-colors bg-[#FBF6EA]/30 relative">
                        <input type="file" 
                               name="cv_file" 
                               id="cv_file" 
                               required
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               onchange="previewCvFileName(this)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-2 pointer-events-none">
                            <span class="text-3xl block">📎</span>
                            <span id="cvFileName" class="text-xs font-bold text-[#2B2118] block">
                                اضغط هنا لاختيار ملف السيرة الذاتية
                            </span>
                            <span class="text-[11px] text-[#8F7B63] block">
                                يُقبل ملفات PDF أو Word أو صور بحد أقصى 10 ميجابايت
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Message / Experience Notes -->
                <div class="space-y-1.5">
                    <label for="message" class="block text-xs font-bold text-[#2B2118]">
                        نبذة عن خبراتك السابقة في المبيعات أو الموضة <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                    </label>
                    <textarea name="message" 
                              id="message" 
                              rows="3" 
                              placeholder="اذكر بإيجاز خبراتك السابقة في متاجر الملابس، خدمة العملاء، أو التنسيق..."
                              class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] text-xs text-[#2B2118] focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors bg-[#FBF6EA]/30">{{ old('message') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-6 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <span>إرسال طلب التوظيف</span>
                </button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function selectJobPosition(title) {
        const input = document.getElementById('position');
        if (input) {
            input.value = title;
            document.getElementById('apply-form').scrollIntoView({ behavior: 'smooth' });
            input.focus();
        }
    }

    function previewCvFileName(input) {
        const label = document.getElementById('cvFileName');
        if (input.files && input.files[0]) {
            label.textContent = ` تم اختيار: ${input.files[0].name}`;
            label.classList.add('text-[#A9552F]');
            label.classList.remove('text-[#2B2118]');
        }
    }
</script>
@endpush
@endsection
