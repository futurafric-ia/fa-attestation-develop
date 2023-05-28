const mix = require('laravel-mix')

mix
  .js('resources/js/app.js', 'public/build')
  .js('resources/js/indeterminate-checkbox', 'public/build')
  .postCss(
    'resources/css/app.css',
    'public/build',
    require('./postcss.config').plugins
  )
  .copyDirectory('resources/static', 'public/static')
  .sourceMaps()

if (mix.inProduction()) {
  mix.version()
}
