<?php

if (!function_exists('get_active_currency')) {
    /**
     * Get the active currency code (Always EGP)
     */
    function get_active_currency(): string
    {
        return 'EGP';
    }
}

if (!function_exists('get_exchange_rate')) {
    /**
     * Get exchange rate (Fixed to 1.0 for EGP)
     */
    function get_exchange_rate(): float
    {
        return 1.0;
    }
}

if (!function_exists('convert_price')) {
    /**
     * Convert price (Identity 1:1 for EGP)
     */
    function convert_price(float $amount, ?string $currency = null): float
    {
        return $amount;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a price in Egyptian Pounds (XX.XX ج.م)
     */
    function format_currency(?float $amount, ?string $currency = null): string
    {
        $amount = (float) ($amount ?? 0.0);
        return number_format($amount, 2) . ' ج.م';
    }
}
