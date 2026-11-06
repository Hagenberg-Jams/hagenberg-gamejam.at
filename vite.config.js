import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

// Builds _media/app.css and _media/app.js. The other files in _media must stay.
export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: '_media',
        emptyOutDir: false,
        rollupOptions: {
            input: { styles: 'resources/assets/app.css', app: 'resources/assets/app.js' },
            output: {
                entryFileNames: '[name].js',
                assetFileNames: 'app.[ext]',
            },
        },
    },
});
