/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
    content: [
        './resources/**/*.blade.php', // 对于 Laravel Blade 文件
        './resources/views/*.blade.php', // 对于 Laravel Blade 文件
        // './resources/**/*.js',         // 对于 JavaScript 文件
        // './resources/**/*.vue',        // 对于 Vue 文件（如果使用）
        // './public/**/*.php',          // 对于 HTML 文件
    ],
    theme: {
        extend: {
            screens: {
                'xxx':'1px',
                'xxs': '370px', // 新增自定義的 400px 斷點
                'xs': '419px', // 新增自定義的 400px 斷點
                'ss': '519px', // 新增自定義的 400px 斷點
            },
            fontFamily: {
                sans: ['Inter', 'Noto Sans TC', 'Microsoft JhengHei', 'Arial'],
            },
        },
    },
    plugins: [],
}


