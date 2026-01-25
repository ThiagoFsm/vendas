const mix = require('laravel-mix');
const {options} = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        hmrOptions: {
            host: 'localhost', // navegador vai procurar o HMR aqui
            port: 8080         // porta padrão do dev-server do Mix
        }
    })
    .webpackConfig({
        devServer: {
            host: '0.0.0.0',    // faz o servidor ouvir em todas as interfaces do container
            port: 8080,
            allowedHosts: 'all'
        }
    });
