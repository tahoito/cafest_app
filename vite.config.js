import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'src/resources/css/app.css',
        'src/resources/js/app.js',
      ],
      publicDirectory: 'src/public', 
      buildDirectory: 'build',
      refresh: true,
    }),
  ],
})
