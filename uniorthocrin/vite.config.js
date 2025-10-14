import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
                input: ['resources/css/app.css', 'resources/css/admin-modern.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '172.30.242.88', // Isso vai funcionar já que conseguimos acessar
            port: 5173,
        },
    },
});