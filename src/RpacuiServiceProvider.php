<?php

namespace Codewiser\Rpacui;

use Illuminate\Support\ServiceProvider;

class RpacuiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../public/vendor/rpac' => public_path('vendor/rpac')
        ], 'rpacui');

        $this->publishes([
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers/Rpac')
        ], 'rpacui');


        $this->loadViewsFrom(
            __DIR__.'/../resources/views', 'rpacui'
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/rpac.php');

    }
}
