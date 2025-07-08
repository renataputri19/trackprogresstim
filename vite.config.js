import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/homepage.css',
                'resources/js/app.js',
                'resources/js/homepage.js',
                'public/css/new-homepage/homepage.css',
                'public/js/new-homepage/homepage.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
