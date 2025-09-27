/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'primary-dark': '#205781',
                'primary': '#4F959D',
                'primary-light': '#98D2C0',
                'background-light': '#F6F8D5',
                'white': '#ffffff',
                'text-dark': '#333333',
                'text-darker': '#111111',
                'text-light': '#666666',
                'error': '#dc3545',
                'success': '#28a745',
                'warning': '#ffc107',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}; 