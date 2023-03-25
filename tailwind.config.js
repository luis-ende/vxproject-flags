const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                'primary': '#9900ff',
                'vxproject-primary': '#9900ff',
                'vxproject-secondary': '#ff00ff',
            },
            fontFamily: {
                sans: ['Questrial', ...defaultTheme.fontFamily.sans],
                montserrat: ['Montserrat']
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
