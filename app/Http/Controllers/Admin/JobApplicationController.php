<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\JobApplicationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    public function __construct(
        protected JobApplicationService $jobApplicationService
    ) {}

    /**
     * Display a listing of job applications with filters.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $branchId = $request->query('branch_id') ? (int) $request->query('branch_id') : null;

        $applications = $this->jobApplicationService->paginate($status, $branchId, 15);
        $branches = Branch::select('id', 'name')->ordered()->get();

        return view('admin.job-applications.index', compact('applications', 'branches', 'status', 'branchId'));
    }

    /**
     * Display the specified job application details.
     */
    public function show(int $id): View
    {
        $application = $this->jobApplicationService->find($id);
        return view('admin.job-applications.show', compact('application'));
    }

    /**
     * Update the review status of the application.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ], [
            'status.required' => 'يرجى تحديد حالة الطلب.',
            'status.in' => 'الحالة المحددة غير صحيحة.',
        ]);

        $application = $this->jobApplicationService->find($id);
        $this->jobApplicationService->updateStatus($application, $validated['status']);

        return back()->with('success', 'تم تحديث حالة طلب التوظيف بنجاح.');
    }

    /**
     * Download applicant CV.
     */
    public function downloadCv(int $id): StreamedResponse
    {
        $application = $this->jobApplicationService->find($id);
        return $this->jobApplicationService->downloadCv($application);
    }

    /**
     * Remove the specified job application.
     */
    public function destroy(int $id): RedirectResponse
    {
        $application = $this->jobApplicationService->find($id);
        $this->jobApplicationService->delete($application);

        return redirect()
            ->route('admin.job-applications.index')
            ->with('success', 'تم حذف طلب التوظيف بنجاح.');
    }
}
