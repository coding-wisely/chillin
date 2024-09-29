/*
|-------------------------------------------------------------------------------
| Production config                       https://maizzle.com/docs/environments
|-------------------------------------------------------------------------------
|
| This is the production configuration that Maizzle will use when you run the
| `npm run build` command. These settings will be merged on top of the base
| `config.js`, so you only need to add the options that are changing.
|
*/

/** @type {import('@maizzle/framework').Config} */
export default {

  build: {
    content: ['src/templates/**/*.{html,blade.php}'],
    output: {
      path: '../resources/views/emails',
      extension: 'blade.php'
    },

  },
  static: {
    source: ['src/images/**/*.*'],
    destination: '../../../public/images/emails',
  },
  css: {
    inline: true,
    purge: true,
    shorthand: true,

  },
  prettify: true,
}
