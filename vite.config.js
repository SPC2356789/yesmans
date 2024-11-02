import {defineConfig} from 'vite';

import laravel from 'laravel-vite-plugin';


export default defineConfig({
    // base: 'easy-ibex-nearly.ngrok-free.app',
    host: '0.0.0.0',
    server: {
        host: '0.0.0.0',
        base: '/',
        // strictPort : true,
        // port:443,
        // https:true,
        hmr: {
            // protocol: 'wss', // 使用 WebSocket Secure
            base: '/',
            // host: 'communal-malamute-eternal.ngrok-free.app', // 設定默認值為 'localhost'
            // host: 'palmer-another-expertise-cardiff.trycloudflare.com', // 設定默認值為 'localhost'
            // clientPort: 443,
            // port: 80,
            host: 'yesman.com',  // 設定默認值為 'localhost'

        },
        // proxy: {
        //     '/api': {
        //         target: 'https://communal-malamute-eternal.ngrok-free.app', // 確保這是正確的 API 地址
        //         changeOrigin: true,
        //         secure: false, // 如果是自簽名證書可以設置為 false
        //         ws: true, // 如果需要 WebSocket 支持
        //     },
        // },

    },
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/about.js',
                'resources/js/home.js',
                'resources/js/about.js',
                'resources/newLayout/js/scripts.js',
                'resources/newLayout/css/styles.css',],
            refresh: true,

        }),

    ],

});
