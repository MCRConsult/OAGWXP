import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import commonjs from "vite-plugin-commonjs";

export default defineConfig({
    plugins: [
        commonjs(),
        vue(),
        laravel({
            input: ["resources/sass/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['jquery'],
    },
    resolve: {
        alias: {
            "@": "/resources/js",
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    build: {
        rollupOptions: {
            external: ["popper.js"],
        },
        commonjsOptions: { transformMixedEsModules: true },
    },
});
