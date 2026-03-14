const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css', {
        sassOptions: {
            quietDeps: true,
        },
    })
    .options({
        // Força o Dart Sass a não emitir NENHUM aviso no terminal
        terser: { extractComments: false },
        processCssUrls: true,
    })
    .webpackConfig({
        stats: {
            children: false,
            warnings: false, // Remove avisos do log do Webpack
            errors: true,
        },
    });
