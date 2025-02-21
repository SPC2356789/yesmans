import {defineConfig} from 'vite';

import laravel from 'laravel-vite-plugin';

import addVersion from 'vite-plugin-add-version';
import version from './package.json'
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
const buildVersion = `${version['version'].replace(/\./g, '_')}_${(new Date()).getTime()}`; //customer_version
export default defineConfig({

    server: {
        host: '127.0.0.1',
        base: '/',
        hmr: {
            base: '/',
            host: '127.0.0.1',
            overlay: false,  // 禁用 HMR 錯誤提示
        },
    },

    plugins: [
        laravel({
            input: [
                // 'node_modules/choices.js/public/assets/scripts/choices.min.js', // 手動加載 Choices.js
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
        addVersion(buildVersion),
        vuetify({
            autoImport: true,
        // styles:{
        //         configFile:'resources/css/setting.scss'
        // }

        }), // 確保這裡正確配置了
    ],

    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor'; // 把所有第三方套件放進 vendor.js
                    }
                    if (id.includes('core')) {
                        return 'core'; // 分離 tools.js
                    }
                },
            },
        },
    },
});
