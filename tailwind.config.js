/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
    content: [
        './resources/**/*.blade.php',   // 掃描所有 Blade 檔案
        './resources/**/*.{vue,js,ts,jsx,tsx}',  // 掃描所有 Vue, JS, TS 等檔案
        './resources/views/**/*.vue',   // 確保 views 內的 Vue 檔案被掃描
        './resources/js/**/*.vue',  // 確保 js 內的 Vue 檔案被掃描
        './vendor/filament/**/*.blade.php',  // Filament 插件的 Blade 檔案
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


