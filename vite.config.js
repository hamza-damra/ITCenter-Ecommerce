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
    build: {
        // Enable default esbuild minification (faster than terser)
        minify: 'esbuild',
        // Optimize chunk size
        chunkSizeWarningLimit: 1000,
        // Rollup options for better bundling
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Split node_modules into vendor chunk
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
