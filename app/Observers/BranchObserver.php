<?php

namespace App\Observers;

use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class BranchObserver
{
    public function created(Branch $branch): void
    {
        $this->clearBranchCaches();
    }

    public function updated(Branch $branch): void
    {
        $this->clearBranchCaches();
    }

    public function saved(Branch $branch): void
    {
        $this->clearBranchCaches();
    }

    public function deleted(Branch $branch): void
    {
        $this->clearBranchCaches();
    }

    public function restored(Branch $branch): void
    {
        $this->clearBranchCaches();
    }

    protected function clearBranchCaches(): void
    {
        Cache::forget('storefront_home_branches');
        Cache::forget('site_branches');
    }
}
