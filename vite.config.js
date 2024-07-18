import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // flatpickrを読み込む
                'resources/js/flatpickr.js',
            ],
            refresh: true,
        }),
    ],
});
