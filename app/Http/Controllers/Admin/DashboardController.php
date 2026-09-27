<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AnalyticsRepository;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected AnalyticsRepository $analyticsRepo
    ) {}

    public function index(): View
    {
        $metrics = $this->analyticsRepo->getDashboardMetrics();
        $topProducts = $this->analyticsRepo->getTopProducts(5);
        $whatsappChart = $this->analyticsRepo->getWhatsappClicksChart(7);
        $branchStats = $this->analyticsRepo->getBranchPerformance();

        return view('admin.index', compact('metrics', 'topProducts', 'whatsappChart', 'branchStats'));
    }
}
