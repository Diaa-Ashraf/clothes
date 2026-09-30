<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SettingsService::class, function () {
            return new \App\Services\SettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\Banner::observe(\App\Observers\BannerObserver::class);
        \App\Models\Deal::observe(\App\Observers\DealObserver::class);
        \App\Models\Bundle::observe(\App\Observers\BundleObserver::class);
        \App\Models\Branch::observe(\App\Observers\BranchObserver::class);
        \App\Models\Magazine::observe(\App\Observers\MagazineObserver::class);

        // Implicitly grant 'super_admin' all permissions
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        // Rate Limiting for checkout & coupons
        \Illuminate\Support\Facades\RateLimiter::for('checkout', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(20)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('coupon-apply', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(20)->by($request->ip());
        });

        // Share global categories with site header and layouts
        view()->composer(['layouts.site-sections.header', 'layouts.site'], function ($view) {
            $siteNavCategories = \Illuminate\Support\Facades\Cache::remember('site_nav_categories', 1800, function () {
                return \App\Models\Category::withCount('activeProducts')
                    ->get();
            });
            $view->with('siteNavCategories', $siteNavCategories);
        });
    }
}
