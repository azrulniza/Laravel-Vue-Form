import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Add the includePaths option for Sass to resolve aliases
                additionalData: `@use '@/assets/layout/layout' as *;`,
                quietDeps: true,  // This line enables the --quiet-deps behavior
            }
        }
    },
    scss: {
        api: 'modern-compiler',
        quietDeps: true
      },
});
