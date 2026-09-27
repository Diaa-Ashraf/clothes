import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './public/assets/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Al-Tawheed Signature Signboard Palette (أحمر اليافطة الناري والبرتقالي الساطع)
                primary: {
                    50: '#FEF2F2',
                    100: '#FEE2E2',
                    200: '#FECACA',
                    300: '#FCA5A5',
                    400: '#F87171',
                    500: '#EF4444',
                    600: '#DC2626',
                    700: '#B91C1C',
                    800: '#991B1B',
                    900: '#7F1D1D',
                    DEFAULT: '#DC2626', // أحمر التوحيد الناري من اليافطة
                    dark: '#991B1B',
                    light: '#EF4444',
                },
                // Electric Amber / Orange Accent (برتقالي اليافطة المضيء "ماركت وألبان")
                accent: {
                    50: '#FFF7ED',
                    100: '#FFEDD5',
                    200: '#FED7AA',
                    300: '#FDBA74',
                    400: '#FB923C',
                    500: '#F97316',
                    600: '#EA580C',
                    700: '#C2410C',
                    DEFAULT: '#F97316', // برتقالي اليافطة الصريح
                },
                // Warm Cream Backgrounds (درجات القشطة والزبادي والكانفاس المريح)
                canvas: {
                    base: '#FBF9F5',
                    subtle: '#F4EFE6',
                    dark: '#EAE2D5',
                },
                surface: {
                    card: '#FFFFFF',
                    base: '#FFFFFF',
                    hover: '#F9F7F2',
                    border: '#EDE7DC',
                    subtle: '#F6F3EC',
                    champagne: '#E5DDCF',
                },
                // Deep Charcoal Typography for maximum legibility
                charcoal: {
                    heading: '#111827',
                    DEFAULT: '#1F2937',
                    secondary: '#4B5563',
                    muted: '#6B7280',
                    light: '#9CA3AF',
                },
                // Legacy compatibility tokens
                gold: {
                    champagne: '#D97706',
                    satin: '#B45309',
                    bronze: '#92400E',
                    dark: '#78350F',
                    DEFAULT: '#D97706',
                },
                obsidian: {
                    DEFAULT: '#111827',
                    secondary: '#4B5563',
                    muted: '#6B7280',
                },
                espresso: {
                    main: '#111827',
                    secondary: '#4B5563',
                    muted: '#6B7280',
                },
                emerald: {
                    wa: '#25D366',
                },
                rose: {
                    promo: '#DC2626',
                },
            },
            fontFamily: {
                sans: ['Cairo', 'Alexandria', 'Readex Pro', ...defaultTheme.fontFamily.sans],
                arabic: ['Cairo', 'Alexandria', 'Readex Pro', 'sans-serif'],
                display: ['Cairo', 'Alexandria', 'sans-serif'],
                royal: ['Cairo', 'serif'],
            },
            borderRadius: {
                'card': '1rem',
                'btn': '0.75rem',
                'pill': '9999px',
            },
            boxShadow: {
                'card-soft': '0 2px 12px -2px rgba(115, 14, 31, 0.05), 0 4px 16px -2px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 12px 28px -4px rgba(115, 14, 31, 0.10), 0 8px 16px -4px rgba(0, 0, 0, 0.06)',
                'maroon-glow': '0 4px 20px -2px rgba(115, 14, 31, 0.35)',
                'amber-glow': '0 4px 20px -2px rgba(217, 119, 6, 0.35)',
                'card-luxury': '0 4px 20px rgba(0, 0, 0, 0.04)',
                'gold-subtle': '0 4px 16px rgba(217, 119, 6, 0.18)',
            },
            keyframes: {
                'marquee-scroll': {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'pulse-subtle': {
                    '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                    '50%': { transform: 'scale(1.05)', opacity: '0.92' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-6px)' },
                },
                'glow-pulse': {
                    '0%, 100%': { boxShadow: '0 0 15px rgba(220, 38, 38, 0.4)' },
                    '50%': { boxShadow: '0 0 25px rgba(249, 115, 22, 0.8)' },
                },
                'shimmer': {
                    '100%': { transform: 'translateX(100%)' },
                }
            },
            animation: {
                'marquee': 'marquee-scroll 25s linear infinite',
                'pulse-subtle': 'pulse-subtle 2s infinite ease-in-out',
                'float': 'float 3s ease-in-out infinite',
                'glow': 'glow-pulse 2s infinite ease-in-out',
            },
        },
    },

    plugins: [forms],
};
