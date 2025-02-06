import {defineConfig} from 'vite';

import laravel from 'laravel-vite-plugin';

import addVersion from 'vite-plugin-add-version';
import version from './package.json'

const buildVersion = `${version['version'].replace(/\./g, '_')}_${(new Date()).getTime()}`; //customer_version
export default defineConfig({
    host: '0.0.0.0',
    server: {
        host: '0.0.0.0',
        base: '/',
        hmr: {
            base: '/',
            host: 'yesman.com',  // 設定默認值為 'localhost'
        },
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        addVersion(buildVersion),
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
