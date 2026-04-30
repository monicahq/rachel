import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
  // Load env file based on `mode` in the current working directory.
  // Set the third parameter to '' to load all env regardless of the `VITE_` prefix.
  const env = loadEnv(mode, process.cwd(), '');
  return {
    plugins: [
      laravel({
        input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/webauthn.js'],
        refresh: true,
      }),
      tailwindcss(),
    ],
    server: {
      cors: true,
    },
    build: {
      sourcemap: env.VITE_PROD_SOURCE_MAPS,
    },
  };
});
