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
    server: {
        host: '0.0.0.0', // Permite conexiones externas en la red local
        port: 5173, // Asegura que Vite use el puerto correcto
        strictPort: true, // No cambia el puerto si está ocupado
        hmr: {
            host: '209.126.85.168', // Usa la IP de tu máquina
            protocol: 'ws', // WebSocket sin cifrar (si usas HTTPS, cambia a 'wss')
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
