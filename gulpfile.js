var elixir = require('laravel-elixir');

elixir.config.sourcemaps = true;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

var theme = 'default',
    themePath = 'theme/' + theme,
    resourcePath = 'public/' + themePath;


elixir(function (mix) {
    mix.less(['common.less', 'account.less'], resourcePath + '/css/');
});


elixir(function (mix) {
    mix.scripts(['libs/jquery.min.js'], resourcePath + '/js/libs/jquery.min.js');
    mix.scripts(['libs/jquery-migrate.js'], resourcePath + '/js/libs/jquery-migrate.js');
    mix.scripts(['libs/Class.js'], resourcePath + '/js/libs/Class.js');

    mix.scripts(['renders/BRender.js'], resourcePath + '/js/renders/BRender.js');
    mix.scripts(['renders/AccountRender.js'], resourcePath + '/js/renders/AccountRender.js');
});