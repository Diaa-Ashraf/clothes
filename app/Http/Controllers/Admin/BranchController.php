<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBranchRequest;
use App\Http\Requests\Admin\UpdateBranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $branchService
    ) {}

    /**
     * Display a listing of branches.
     */
    public function index(): View
    {
        $branches = $this->branchService->paginate(15);
        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create(): View
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $this->branchService->create(
            $request->validated(),
            $request->file('image')
        );

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'تم إضافة الفرع بنجاح.');
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(int $id): View
    {
        $branch = $this->branchService->find($id);
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(UpdateBranchRequest $request, int $id): RedirectResponse
    {
        $branch = $this->branchService->find($id);
        
        $this->branchService->update(
            $branch,
            $request->validated(),
            $request->file('image')
        );

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'تم تحديث بيانات الفرع بنجاح.');
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $branch = $this->branchService->find($id);
        $this->branchService->delete($branch);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'تم حذف الفرع بنجاح.');
    }
}
