const mix = require('laravel-mix');

const purgecss = require('@fullhuman/postcss-purgecss')({

    // Specify the paths to all of the template files in your project
    content: [
        './src/**/*.html',
        './src/**/*.vue',
        './src/**/*.php',
        './src/**/*.blade.php',
    ],
    extractors: [
        {
            extractor: class {
                static extract(content) {
                    return content.match(/[A-Za-z0-9-_:/]+/g) || [];
                }
            },

            // Specify all of the extensions of your template files
            extensions: ['html', 'vue', 'php', '.blade.php']
        }
    ]
})

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

mix.postCss('resources/css/myriad.css', 'assets/css', [
    require('tailwindcss'),
    ...process.env.NODE_ENV === 'production'
        ? [purgecss]
        : []
]);

mix.js('resources/js/myriad.js', 'assets/js');
