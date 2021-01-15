<?php

namespace Akkurate\LaravelMedia;

use Akkurate\LaravelMedia\Console\MediaSeed;
use Illuminate\Support\ServiceProvider;

/**
 * Access service provider
 *
 */
class LaravelMediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'media');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/laravel-media.php' => config_path('laravel-media.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('js/vendor/media'),
        ], 'js');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MediaSeed::class
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-media.php',
            'laravel-media'
        );
    }
}
