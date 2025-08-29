/*
 |--------------------------------------------------------------------------
 | PostCSS configuration
 |--------------------------------------------------------------------------
 |
 | You can configure PostCSS and add plugins in this
 | configuration file.
 |
 */

 module.exports = {
    plugins: [
      require('tailwindcss'),
      require('autoprefixer'),
      require('postcss-import'),
      // require('tailwindcss/nesting')(require('postcss-nesting')),
    ],
  };
