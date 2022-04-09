const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const Clean = require("./limpar_builds")

if(mix.inProduction()) {
    mix.extend('clean', new Clean);
    mix.clean();
}

mix.js('resources/js/app.js', 'public/js')
    .options({
        terser: {
            extractComments: false,
        }
    })
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).webpackConfig({
        output: {
            chunkFilename: 'js/[name].js?id=[chunkhash]',
        },
    }).browserSync({
        // fixes pagination urls otherwise they get re-written to use the service `container_name`...
        host: 'localhost:8000',
        // service container_name...
        proxy: 'nginx',
        // matches the port number exposed earlier...
        port: 3000,
        open: false,
    });
