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

mix
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/welcome.js', 'public/js')
    .js('resources/js/wharehouse.js', 'public/js')
    .js('resources/js/general.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .sass('resources/sass/wharehouse.scss', 'public/css/wharehouse.css')
    .sass('resources/sass/basket.scss', 'public/css/basket.css')
    .sass('resources/sass/admin.scss', 'public/css/admin.css')
    .sass('resources/sass/header.scss', 'public/css/header.css')
    .sass('resources/sass/supermodal.scss', 'public/css/supermodal.css')
    .sass('resources/sass/shop.scss', 'public/css/shop.css')
    .sass('resources/sass/newproduct.scss', 'public/css/newproduct.css')
    .sass('resources/sass/welcome.scss', 'public/css/welcome.css');