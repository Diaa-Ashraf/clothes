<?php

namespace App\Observers;

use App\Models\Banner;
use Illuminate\Support\Facades\Cache;

class BannerObserver
{
    public function created(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    public function updated(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    public function saved(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    public function deleted(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    public function restored(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    protected function clearBannerCaches(): void
    {
        Cache::forget('storefront_home_banners');
        Cache::forget('storefront_home_collection_banners');
        Cache::forget('homepage_banners');
        Cache::forget('homepage_banners_ar');
        Cache::forget('homepage_banners_en');
    }
}
