<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //注册Blade模板指令

        Blade::directive('requireResources', function ($expression) {
            return "<?php echo App\\Libs\\Apikit::require_resources{$expression}; ?>";
        });

        Blade::directive('requireUrl', function ($expression) {
            return "<?php echo App\\Libs\\Apikit::require_url{$expression}; ?>";
        });

        Blade::directive('includeBlade', function ($expression) {
            return "<?php echo App\\Libs\\Apikit::include_blade{$expression}; ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
