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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'text-slide': {
                    '0%, 20%': { transform: 'translateY(0%)' },
                    
                    '50%, 70%': { transform: 'translateY(-50%)' },
          
                    '100%': { transform: 'translateY(-100%)' },
                },
            },
            animation: {
                'text-slide': 'text-slide 8s cubic-bezier(0.83, 0, 0.17, 1) infinite',
            },
        },
    },

    plugins: [forms],
};