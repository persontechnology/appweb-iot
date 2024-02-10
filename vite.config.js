import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
/*
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
*/
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/laravel-echo-setup.js"],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public', // Configura el directorio de salida
        assetsDir: 'configEcho', // Opcional: configura un subdirectorio para los activos si es necesario
        emptyOutDir: false,
      },
});
