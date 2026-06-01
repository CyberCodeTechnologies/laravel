import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/loader.css',
                'resources/js/app.js',
                'resources/js/artwork-actions.js',
                'resources/js/currency.js',
                'resources/js/loader.js',
                'resources/js/order-form.js',
                'resources/js/artist-selection-fix.js',
                'resources/css/admin-zoho.css',
                'resources/js/admin-zoho.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        port: 5174,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
