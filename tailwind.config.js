import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/filament/filament/resources/**/*.blade.php',
        './vendor/filament/actions/resources/**/*.blade.php',
        './vendor/filament/forms/resources/**/*.blade.php',
        './vendor/filament/infolists/resources/**/*.blade.php',
        './vendor/filament/notifications/resources/**/*.blade.php',
        './vendor/filament/widgets/resources/**/*.blade.php',
        './vendor/filament/schemas/resources/**/*.blade.php',
        './vendor/filament/support/resources/**/*.blade.php',
        './vendor/filament/tables/resources/**/*.blade.php',
        './vendor/filament/livewire/resources/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
