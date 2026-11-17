let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .scripts([
        'node_modules/autonumeric/dist/autoNumeric.min.js',
        'node_modules/moment/min/moment-with-locales.min.js',
        'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        'node_modules/angular/angular.min.js',
    ], 'public/js/libraries/library.js')
    .scripts('resources/assets/js/angular/angular.js', 'public/js/angular/app.js')
    .scripts(['resources/assets/js/class/modal.js'], 'public/js/custom.min.js')
    .scripts(['resources/assets/js/angular/room-detail/room-detail.controller.js'], 'public/js/angular/room-detail/room-detail.min.js')
    .scripts(['resources/assets/js/angular/room-history-detail/room-history-detail.controller.js'], 'public/js/angular/room-history-detail/room-history-detail.min.js')
    .scripts(['resources/assets/js/angular/equipment-detail/equipment-detail.controller.js'], 'public/js/angular/equipment-detail/equipment-detail.min.js')
    .scripts(['resources/assets/js/angular/equipment-history-detail/equipment-history-detail.controller.js'], 'public/js/angular/equipment-history-detail/equipment-history-detail.min.js')
    .copy('node_modules/admin-lte/dist/js/app.min.js', 'public/js/admin-lte.min.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin-lte.scss', 'public/css')
    .sass('resources/assets/sass/custom.scss', 'public/css')
    .sass('resources/assets/sass/print-table-1.scss', 'public/css')
    .sass('resources/assets/sass/print-table-2.scss', 'public/css')
    .version();