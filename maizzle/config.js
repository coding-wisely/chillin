/*
|-------------------------------------------------------------------------------
| Development config                      https://maizzle.com/docs/environments
|-------------------------------------------------------------------------------
|
| This is the base configuration that Maizzle will use when you run commands
| like `npm run build` or `npm run serve`. Additional config files will
| inherit these settings, and can override them when necessary.
*/
import autoprefixer from 'autoprefixer'

/** @type {import('@maizzle/framework').Config} */
export default {
  build: {
    postcss: {
      plugins: [
        autoprefixer,
      ]
    },
    content: ['src/templates/**/*.{html,blade.php}'],
    output: {
      path: '../resources/views/emails',
      extension: 'blade.php'
    },
    static: {
      source: ['src/images/**/*.*'],
      destination: '../../../public/images/emails',
    },
  },
}
