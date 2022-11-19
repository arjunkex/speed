const {join} = require('path')
const mix = require('laravel-mix')

mix
  .js('resources/js/central.js', 'public/js')
  .js('resources/js/tenant.js', 'public/js')
  .vue()
  .sass('resources/sass/app.scss', 'public/css')

if (mix.inProduction()) {
  mix.disableNotifications()
    .version();
} else {
  mix.sourceMaps()
}

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.wav$/i,
        use: [
          {
            loader: 'file-loader',
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': join(__dirname, './resources/js')
    }
  },
})
