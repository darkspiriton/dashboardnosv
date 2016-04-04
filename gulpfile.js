var elixir = require('laravel-elixir');
require('laravel-elixir-stylus');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts(
        ['satellizer.js'],
        'public/app/lib/satellizer.js'
    );
    mix.stylus('loginStyles.styl' , 'public/css/loginStyles.min.css');
    mix.stylus('styles.styl' , 'public/css/styles.min.css');
    mix.stylus('toastr.styl' , 'public/css/toastr.min.css');
});

elixir(function(mix) {
    //mix.version("css/styles.css");
});