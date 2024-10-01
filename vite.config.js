import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            detectTls:'yesman.com',
            input: ['resources/css/app.css', 'resources/js/app.js','resources/newLayout/css/refer.css'],
            refresh: true,
            server: {
                host: '0.0.0.0', // 使伺服器可從外部網絡訪問
                port: 3000,      // 指定端口，根據需要修改
                strictPort: true // 如果指定端口已被占用，終止進程
            }
        }),
    ],
});
