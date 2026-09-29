<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function saved(Product $product): void
    {
        $this->clearProductCaches();
    }

    public function deleted(Product $product): void
    {
        $this->clearProductCaches();
    }

    protected function clearProductCaches(): void
    {
        $locales = ['ar', 'en'];
        $limits = [4, 6, 8, 12, 16];

        foreach ($locales as $locale) {
            foreach ($limits as $limit) {
                Cache::forget("homepage_featured_products_{$locale}_{$limit}");
                Cache::forget("homepage_latest_products_{$locale}_{$limit}");
                Cache::forget("homepage_bestsellers_{$locale}_{$limit}");
                Cache::forget("homepage_daily_deals_{$locale}_{$limit}");
            }
            Cache::forget('site_nav_categories_' . $locale);
            Cache::forget('site_homepage_categories_' . $locale);
        }

        // Storefront home and category cache keys
        Cache::forget('storefront_home_featured_products');
        Cache::forget('storefront_home_latest_products');
        Cache::forget('storefront_home_deals');
        Cache::forget('storefront_home_categories');
        Cache::forget('storefront_sidebar_categories');
    }
}
