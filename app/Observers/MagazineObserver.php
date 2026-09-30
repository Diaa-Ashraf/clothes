<?php

namespace App\Observers;

use App\Models\Magazine;
use Illuminate\Support\Facades\Cache;

class MagazineObserver
{
    public function created(Magazine $magazine): void
    {
        $this->clearMagazineCaches();
    }

    public function updated(Magazine $magazine): void
    {
        $this->clearMagazineCaches();
    }

    public function saved(Magazine $magazine): void
    {
        $this->clearMagazineCaches();
    }

    public function deleted(Magazine $magazine): void
    {
        $this->clearMagazineCaches();
    }

    public function restored(Magazine $magazine): void
    {
        $this->clearMagazineCaches();
    }

    protected function clearMagazineCaches(): void
    {
        Cache::forget('storefront_active_magazines');
        Cache::forget('site_magazines');
    }
}
