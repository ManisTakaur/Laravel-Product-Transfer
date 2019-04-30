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

mix.styles([
        'public/styleResource/bower_components/datatables.net-bs/css/datatables.css',
        'public/styleResource/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'public/styleResource/dist/css/skins/skin-blue.css',
        'public/styleResource/bower_components/morris.js/morris.css',
        'public/styleResource/bower_components/select2/dist/css/select2.min.css',
        'public/styleResource/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'public/styleResource/bower_components/bootstrap-daterangepicker/daterangepicker.css',
        'public/styleResource/dist/css/AdminLTE.css'


    ], 'public/css/everythingCSS.css')

.combine([
        'public/styleResource/bower_components/jquery/dist/jquery.min.js',
        'public/styleResource/bower_components/jquery-ui/jquery-ui.min.js',
        'public/styleResource/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'public/styleResource/bower_components/moment/min/moment.min.js',
        'public/styleResource/bower_components/bootstrap-daterangepicker/daterangepicker.js',
        'public/styleResource/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'public/styleResource/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'public/styleResource/bower_components/select2/dist/js/select2.full.min.js',
        'public/styleResource/bower_components/fastclick/lib/fastclick.js',
        'public/styleResource/dist/js/adminlte.min.js'

    ], 'public/js/everythingJS.js');
