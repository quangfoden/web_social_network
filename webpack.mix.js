const mix = require('laravel-mix');
var path = require('path');
require('dotenv').config();
mix.js('resources/js/app.js', 'public/assets/js')
    .vue()
    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.jsx?$/,
                    exclude: /node_modules(?!\/foundation-sites)|bower_components/,
                    use: [
                        {
                            loader: 'babel-loader',
                            options: Config.babel()
                        }
                    ]
                }
            ]
        },
        resolve: {
            alias: {
                '@': path.resolve('resources/assets/sass')
            },
            alias: {
                '@resources': path.resolve(__dirname, 'resources/')
            }
        }
    })
    .sass('resources/assets/scss/style.scss', 'public/assets/css')
    .sass('resources/assets/scss/authentication.scss', 'public/assets/css')
    .sass('resources/assets/scss/custom_admin.scss', 'public/assets/css')
    .sass('resources/assets/scss/datatable.scss', 'public/assets/css')