<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreJobApplicationRequest;
use App\Models\Branch;
use App\Services\JobApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function __construct(
        protected JobApplicationService $jobApplicationService
    ) {}

    /**
     * Display careers & available vacancies page with application form.
     */
    public function index(): View
    {
        $branches = Branch::select(['id', 'name'])
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $openPositions = [
            [
                'title' => 'منسق أزياء ومبيعات بالبوتيك (Fashion Stylist)',
                'department' => 'خدمة العملاء والمبيعات الراقية',
                'type' => 'دوام كامل',
                'description' => 'مساعدة العملاء في اختيار التنسيقات وتوافق الألوان والمقاسات بما يلائم أسلوبهم الشخصي وتقديم تجربة تسوق استثنائية.',
            ],
            [
                'title' => 'كاشير ومسؤول نقطة البيع في البوتيك',
                'department' => 'الحسابات وخدمة العملاء',
                'type' => 'دوام كامل',
                'description' => 'إدارة عمليات الدفع النقدي والإلكتروني ومطابقة الفواتير والتعامل الراقي مع رواد البوتيك.',
            ],
            [
                'title' => 'خياط / فني تعديل مقاسات وأزياء',
                'department' => 'قسم التعديلات والتشطيب',
                'type' => 'دوام كامل',
                'description' => 'إجراء تعديلات المقاسات والقصات الدقيقة للزبائن لضمان ظهور القطع بأفضل هيئة على القوام.',
            ],
            [
                'title' => 'مسؤول مراقبة الجودة والتغليف الفاخر',
                'department' => 'التجهيز والشحن',
                'type' => 'دوام كامل',
                'description' => 'فحص القطع والتطريز وتكييس الملابس وتغليفها بعناية فائقة لتصل للعميل بأعلى مستوى من الأناقة.',
            ],
            [
                'title' => 'مصور ومنسق محتوى أزياء وسوشيال ميديا',
                'department' => 'التسويق والميديا',
                'type' => 'دوام كامل / مرن',
                'description' => 'تصوير الموديلات الجديدة وتنسيقات الأطقم وإعداد جلسات التصوير ونشرها على المنصات الرقمية.',
            ],
        ];

        return view('storefront.careers.index', compact('branches', 'openPositions'));
    }

    /**
     * Store submitted job application with uploaded CV.
     */
    public function store(StoreJobApplicationRequest $request): RedirectResponse
    {
        $this->jobApplicationService->create(
            $request->validated(),
            $request->file('cv_file')
        );

        return back()->with('success', __('تم استلام طلب التوظيف بنجاح! سيقوم فريق الموارد البشرية بالتواصل معك بعد مراجعة بياناتك.'));
    }
}
