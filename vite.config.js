import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    build: {
        manifest: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            input: 'resources/js/app.js',
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: ({ name }) => {
                    if (name && name.endsWith('.css')) {
                        return 'css/[name]';
                    }
                    return 'assets/[name]';
                },
            },
        },
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.1.39', // reemplaza con la IP local de tu máquina
            protocol: 'ws',
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        react(),
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
