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
                // Section 5.2 Atelier Warm Palette Tokens
                vanilla: '#F3E9D2',   // Page canvas
                cream: '#FBF6EA',     // Cards, inputs, modals
                linen: '#EADFC8',     // Subtle sections, table stripes
                beige: '#E2D2B3',     // Hover surfaces, chips
                sand: '#CDB78F',      // Borders, dividers

                espresso: {
                    DEFAULT: '#2B2118', // Primary text
                    deep: '#1A110A',
                    heading: '#1A110A',
                    dark: '#2B2118',
                    light: '#382416',
                },
                mocha: {
                    DEFAULT: '#6B5642', // Secondary text
                    secondary: '#6B5642',
                },
                taupe: {
                    DEFAULT: '#8F7B63', // Muted / placeholder
                    muted: '#8F7B63',
                },
                clay: {
                    DEFAULT: '#A9552F', // Terracotta accent (sales, highlights)
                    soft: '#E9CDB8',
                    hover: '#8E4524',
                },
                action: {
                    DEFAULT: '#382416', // Primary button fill
                    hover: '#25170D',
                    ink: '#FBF6EA',     // Primary button text
                },
                status: {
                    success: '#5F6B3A', // Olive
                    warning: '#B8862B', // Ochre
                    danger: '#9E3B2E',  // Brick
                    neutral: '#8F7B63', // Taupe
                },

                // Aliases for compatibility with existing view components
                primary: {
                    50: '#FBF6EA',
                    100: '#F3E9D2',
                    200: '#EADFC8',
                    300: '#E2D2B3',
                    400: '#CDB78F',
                    500: '#382416',
                    600: '#2B2118',
                    700: '#25170D',
                    800: '#1A110A',
                    900: '#100904',
                    DEFAULT: '#382416',
                    dark: '#1A110A',
                    light: '#6B5642',
                },
                accent: {
                    50: '#FDF7F3',
                    100: '#F8ECE4',
                    200: '#E9CDB8',
                    300: '#D9AB8D',
                    400: '#C27C56',
                    500: '#A9552F',
                    600: '#8E4524',
                    700: '#73351A',
                    DEFAULT: '#A9552F',
                },
                canvas: {
                    base: '#F3E9D2',
                    subtle: '#EADFC8',
                    dark: '#E2D2B3',
                },
                surface: {
                    card: '#FBF6EA',
                    base: '#FBF6EA',
                    hover: '#E2D2B3',
                    border: '#CDB78F',
                    subtle: '#EADFC8',
                    champagne: '#EADFC8',
                },
                charcoal: {
                    heading: '#1A110A',
                    DEFAULT: '#2B2118',
                    secondary: '#6B5642',
                    muted: '#8F7B63',
                    light: '#A3907A',
                },
            },
            fontFamily: {
                sans: ['"IBM Plex Sans Arabic"', 'Alexandria', 'Cairo', ...defaultTheme.fontFamily.sans],
                arabic: ['"IBM Plex Sans Arabic"', 'Alexandria', 'Cairo', 'sans-serif'],
                display: ['"IBM Plex Sans Arabic"', 'Alexandria', 'sans-serif'],
            },
            borderRadius: {
                'card': '12px',
                'btn': '36px',
                'pill': '9999px',
                'ghost': '22px',
                'input': '8px',
            },
            borderWidth: {
                'hairline': '1px',
            },
        },
    },

    plugins: [forms],
};
