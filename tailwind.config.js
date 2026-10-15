import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    dark: '#111111',
                    DEFAULT: '#18181B',
                    light: '#F5F5F5',
                    accent: '#3F3F46',
                },
            },
            aspectRatio: {
                'fashion': '3/4',
                'banner': '16/7',
            },
        },
    },

    plugins: [forms],
};
