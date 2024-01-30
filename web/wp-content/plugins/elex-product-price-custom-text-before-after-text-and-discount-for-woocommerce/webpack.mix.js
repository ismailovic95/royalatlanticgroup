const mix = require('laravel-mix');

var LiveReloadWebpackPlugin = require('@kooneko/livereload-webpack-plugin');

mix.webpackConfig({
    plugins: [new LiveReloadWebpackPlugin()]
});

mix.sass('assets/scss/app.scss', 'assets/css/app.css');