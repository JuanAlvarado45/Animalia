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
            colors: {
                primary: {
                    DEFAULT: '#0F766E', // Teal (color principal del logo Animalía)
                    dark: '#0B4F4A',
                    light: '#14B8A6',
                },
                secondary: {
                    DEFAULT: '#F59E0B', // Naranja/ámbar (acento del logo)
                    dark: '#B45309',
                },
            },
        },
    },

    plugins: [forms],
};
