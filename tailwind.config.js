/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
    content: [
        './resources/**/*.blade.php', // 对于 Laravel Blade 文件
        './resources/views/*.blade.php', // 对于 Laravel Blade 文件
        './resources/**/*.js',         // 对于 JavaScript 文件
        './resources/**/*.vue',        // 对于 Vue 文件（如果使用）
        './public/**/*.php',          // 对于 HTML 文件
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['InterVariable', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
}

