import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import reactRefresh from '@vitejs/plugin-react-refresh';

export default defineConfig({
    build: {
        manifest: true,
        rtl: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                entryFileNames: 'js/' + '[name]' + '.js',
            },
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        react(),
        reactRefresh(),
    ],
    resolve: {
        alias: {
            events: 'events',
        },
    },
    define: {
        'process.env': {
            APP_URL: process.env.APP_URL,
        },
    },
});
