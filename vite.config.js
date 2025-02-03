import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import eslint from 'vite-plugin-eslint';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());

    const isSPA = mode === 'spa';
    const apiUrl = env.VITE_API_URL;

console.log(apiUrl)
    return {
        envDir: path.resolve(__dirname),
        plugins: [
            vue(),
            !isSPA && laravel({
                input: ['resources/js/app.js', 'resources/css/app.css'],
                refresh: true,
            }),
            eslint(),
        ].filter(Boolean),

        root: isSPA ? 'resources/js' : '.',
        build: {
            outDir: isSPA ? '../../public/spa' : 'public/build',
            emptyOutDir: true,
        },
        server: {
            watch: {
                usePolling: true,
                ignored: ['!**/resources/js/**'],
            },
            host: env.VITE_HOST || 'localhost',
            port: env.VITE_PORT || 5173,

        },
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js'),
            },
        },

    };
});
