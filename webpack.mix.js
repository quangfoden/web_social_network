const mix = require('laravel-mix');

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
    })
    .sass('resources/assets/scss/style.scss', 'public/assets/css')