const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: ['./storage/framework/views/*.php', './resources/views/**/*.blade.php'],

    theme: {
        extend: {
            fontFamily: {
                // sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Arial', ...defaultTheme.fontFamily.serif],
            },

            fontSize: {
              'tiny': '.88rem',
            },

            colors: {
              'semi-75': 'rgba(0, 0, 0, 0.75)',
              'iron-mountain': '#757575',
            }
        },
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
    },

    plugins: [require('@tailwindcss/ui')],
};
