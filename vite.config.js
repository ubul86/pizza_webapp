import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import eslint from 'vite-plugin-eslint';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        eslint(),
    ],
    build: {
        outDir: path.resolve(__dirname, 'public/build'),
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: path.resolve(__dirname, 'resources/js/app.js'),
                styles: path.resolve(__dirname, 'resources/css/app.css'),
            },
            output: {
                chunkFileNames: '[name].js',
                entryFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        watch: {
            usePolling: true,
            ignored: ['!**/resources/js/**'],
        },
    },
});
