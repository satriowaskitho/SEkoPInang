const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                "primary-orange": "#e44012",
                "dark-brown": "#3f1000",
                "medium-brown": "#581908",
                "light-brown": "#8e4917",
                "cream-yellow": "#f5cf76",
                "bright-orange": "#f58741",
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
