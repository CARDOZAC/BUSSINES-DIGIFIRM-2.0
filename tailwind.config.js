import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'rv-primary': '#1e3a5f',
                'rv-secondary': '#2d6a4f',
                'rv-accent': '#e76f51',
            },
        },
    },

    plugins: [forms, daisyui],

    daisyui: {
        themes: [
            {
                rvtheme: {
                    'primary': '#374151',
                    'secondary': '#4b5563',
                    'accent': '#6b7280',
                    'neutral': '#1f2937',
                    'base-100': '#ffffff',
                    'base-200': '#f3f4f6',
                    'base-300': '#e5e7eb',
                    'base-content': '#111827',
                    'info': '#3b82f6',
                    'success': '#22c55e',
                    'warning': '#f59e0b',
                    'error': '#ef4444',
                },
            },
            'light',
        ],
        darkTheme: false,
    },
};
