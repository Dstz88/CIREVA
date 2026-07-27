import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0E4A7B',
                secondary: '#D4A017',
                success: '#22C55E',
                warning: '#F59E0B',
                danger: '#EF4444',
                info: '#3B82F6',
                background: '#F8FAFC',
                surface: '#FFFFFF',
                border: '#E5E7EB',
                textPrimary: '#111827',
                textSecondary: '#6B7280',
            }
        },
    },

    plugins: [forms],
};
