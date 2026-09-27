<?php

use App\Services\SettingsService;

if (!function_exists('settings')) {
    /**
     * Global settings helper
     */
    function settings(?string $key = null, mixed $default = null): mixed
    {
        $service = app(SettingsService::class);

        if (is_null($key)) {
            return $service;
        }

        return $service->get($key, $default);
    }
}

if (!function_exists('get_site_logo')) {
    /**
     * Get site logo URL with fallback
     */
    function get_site_logo(): ?string
    {
        $logo = settings('site_logo');
        if (empty($logo)) {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://') || str_starts_with($logo, '//')) {
            return $logo;
        }

        if (str_starts_with($logo, '/storage/') || str_starts_with($logo, 'storage/')) {
            return asset(ltrim($logo, '/'));
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            return \Illuminate\Support\Facades\Storage::url($logo);
        }

        if (file_exists(public_path($logo))) {
            return asset($logo);
        }

        return asset('storage/' . ltrim($logo, '/'));
    }
}

if (!function_exists('get_site_favicon')) {
    /**
     * Get browser tab favicon URL with fallback
     */
    function get_site_favicon(): ?string
    {
        $favicon = settings('site_favicon');
        if (empty($favicon)) {
            return get_site_logo();
        }

        if (str_starts_with($favicon, 'http://') || str_starts_with($favicon, 'https://') || str_starts_with($favicon, '//')) {
            return $favicon;
        }

        if (str_starts_with($favicon, '/storage/') || str_starts_with($favicon, 'storage/')) {
            return asset(ltrim($favicon, '/'));
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($favicon)) {
            return \Illuminate\Support\Facades\Storage::url($favicon);
        }

        if (file_exists(public_path($favicon))) {
            return asset($favicon);
        }

        return asset('storage/' . ltrim($favicon, '/'));
    }
}

if (!function_exists('get_og_image')) {
    /**
     * Get social share image (OG Image) URL with fallback
     */
    function get_og_image(): ?string
    {
        $ogImage = settings('og_image');
        if (empty($ogImage)) {
            return asset('assets/site/img/og-cover.png');
        }

        if (str_starts_with($ogImage, 'http://') || str_starts_with($ogImage, 'https://') || str_starts_with($ogImage, '//')) {
            return $ogImage;
        }

        if (str_starts_with($ogImage, '/storage/') || str_starts_with($ogImage, 'storage/')) {
            return asset(ltrim($ogImage, '/'));
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($ogImage)) {
            return \Illuminate\Support\Facades\Storage::url($ogImage);
        }

        if (file_exists(public_path($ogImage))) {
            return asset($ogImage);
        }

        return asset('storage/' . ltrim($ogImage, '/'));
    }
}

