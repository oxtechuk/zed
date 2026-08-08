import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/react/main.tsx'],
            publicDirectory: 'public',
            hotFile: 'public/hot',
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        port: 5159,
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
        watch: {
            ignored: [
                '**/storage/framework/views/**',
                '**/resources/reference/**',
                '**/resources/refrence/**',
            ],
        },
    },
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['react', 'react-dom', 'react-router-dom'],
                    query: ['@tanstack/react-query'],
                    icons: ['lucide-react', 'react-icons'],
                },
            },
        },
    },
});
