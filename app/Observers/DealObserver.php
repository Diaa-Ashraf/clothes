<?php

namespace App\Observers;

use App\Models\Deal;
use Illuminate\Support\Facades\Cache;

class DealObserver
{
    public function created(Deal $deal): void
    {
        $this->clearDealCaches();
    }

    public function updated(Deal $deal): void
    {
        $this->clearDealCaches();
    }

    public function saved(Deal $deal): void
    {
        $this->clearDealCaches();
    }

    public function deleted(Deal $deal): void
    {
        $this->clearDealCaches();
    }

    public function restored(Deal $deal): void
    {
        $this->clearDealCaches();
    }

    protected function clearDealCaches(): void
    {
        $locales = ['ar', 'en'];
        $limits = [4, 6, 8, 10, 12, 16, 20];

        Cache::forget('storefront_home_deals');
        Cache::forget('homepage_deals_list');

        foreach ($locales as $locale) {
            Cache::forget('homepage_deals_list_' . $locale);
            foreach ($limits as $limit) {
                Cache::forget("homepage_daily_deals_{$limit}");
                Cache::forget("homepage_daily_deals_{$locale}_{$limit}");
                Cache::forget("homepage_featured_products_{$locale}_{$limit}");
            }
        }
    }
}
