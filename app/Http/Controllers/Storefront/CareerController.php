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
                'title' => 'كاشير / مسؤول نقطة البيع',
                'department' => 'خدمة العملاء والحسابات',
                'type' => 'دوام كامل',
                'description' => 'إدارة عمليات الدفع الإلكتروني والنقدي، ومراجعة فواتير العملاء بابتسامة وسرعة ودقة.',
            ],
            [
                'title' => 'بائع ألبان وأجبان متخصصة',
                'department' => 'قسم الألبان الطازجة',
                'type' => 'دوام كامل',
                'description' => 'تقديم ووزن وتقطيع الأجبان والقشطة والألبان الطازجة مع الالتزام بأعلى معايير النظافة والتعقيم.',
            ],
            [
                'title' => 'مندوب توصيل طلبات (ديليفري)',
                'department' => 'أسطول التوصيل بالعامرية',
                'type' => 'دوام كامل / جزئي',
                'description' => 'توصيل طلبات المتجر للعملاء في أحياء العامرية وكينج مريوط بأمانة وسرعة، مع الحفاظ على الأكياس المبردة.',
            ],
            [
                'title' => 'أمين مخزن ومسؤول رص بضائع',
                'department' => 'المخازن والإمداد',
                'type' => 'دوام كامل',
                'description' => 'استلام البضائع من الموردين، فحص تواريخ الصلاحية، ورص المنتجات على الأرفف وفق المعايير.',
            ],
            [
                'title' => 'محاسب فرع',
                'department' => 'الإدارة المالية',
                'type' => 'دوام كامل',
                'description' => 'جرد يومي، مطابقة المبيعات النقدية والإلكترونية، ومتابعة سندات الصرف والاستلام.',
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
