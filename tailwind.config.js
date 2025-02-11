/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
    content: [
        './resources/**/*.blade.php', // 对于 Laravel Blade 文件
        './resources/views/*.blade.php', // 对于 Laravel Blade 文件
        // './resources/views/**/*.blade.php', // 对于 Laravel Blade 文件
        './vendor/filament/**/*.blade.php',
        './resources/js/*.js', // js會輸出內容，防止沒吃到
        "./node_modules/flowbite/**/*.js"

    ],
    theme: {
        extend: {
            screens: {
                'xxx': '1px',
                'xxs': '370px', // 新增自定義的 400px 斷點
                'xs': '419px', // 新增自定義的 400px 斷點
                'ss': '519px', // 新增自定義的 400px 斷點
                'xsm': '550px', // 新增自定義的 400px 斷點
                'md965': '965px', // 新增自定義的 400px 斷點
                'lg992': '992px', // 新增 992px 斷點
            },
            colors: {
                'yes-major': '#64A19D',
                'yes-minor': '#b87957',
            },
            fontFamily: {
                sans: ['Inter', 'Noto Sans TC', 'Microsoft JhengHei', 'Arial'],
            },
        },
    },
    plugins: [

    ],
}


