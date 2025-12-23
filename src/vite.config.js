import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        proxy: {
            // Proxy backend routes to the Laravel dev server (adjust port if needed)
            '/user': 'http://127.0.0.1:8000',
            '/store': 'http://127.0.0.1:8000',
        },
    },
});
