import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js"
    ],

    safelist: [
        'bg-slate-200',
        'bg-red-200',
        'bg-green-200',
        'bg-slate-200',
        'bg-orange-200',
        'bg-blue-200',
        'bg-orange-200',
        'bg-red-200',
        'bg-green-200',
        'bg-slate-200',
        'bg-green-200',
        'bg-blue-200',
        'text-gray-700',
        'text-red-700',
        'text-green-700',
        'text-gray-700',
        'text-orange-700',
        'text-blue-700',
        'text-orange-700',
        'text-red-700',
        'text-green-700',
        'text-gray-700',
        'text-green-700',
        'text-blue-700',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require('flowbite/plugin')],
};
