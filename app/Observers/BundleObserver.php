<?php

namespace App\Observers;

use App\Models\Bundle;
use Illuminate\Support\Facades\Cache;

class BundleObserver
{
    public function created(Bundle $bundle): void
    {
        $this->clearBundleCaches();
    }

    public function updated(Bundle $bundle): void
    {
        $this->clearBundleCaches();
    }

    public function saved(Bundle $bundle): void
    {
        $this->clearBundleCaches();
    }

    public function deleted(Bundle $bundle): void
    {
        $this->clearBundleCaches();
    }

    public function restored(Bundle $bundle): void
    {
        $this->clearBundleCaches();
    }

    protected function clearBundleCaches(): void
    {
        $locales = ['ar', 'en'];

        Cache::forget('storefront_home_bundles');
        Cache::forget('homepage_bundles');

        foreach ($locales as $locale) {
            Cache::forget('homepage_bundles_' . $locale);
            Cache::forget('site_bundles_' . $locale);
            Cache::forget('site_active_bundles_' . $locale);
        }
    }
}
