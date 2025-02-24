<?php

namespace YasserElgammal\LaravelEgyptNationalIdParser;

use Illuminate\Support\ServiceProvider;
use YasserElgammal\LaravelEgyptNationalIdParser\Services\NationalIdValidator;

class NationalIdServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('national-id', function ($app) {
            return new NationalIdValidator();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/national-id.php', 'laravel-egypt-national-id-parser'
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/national-id.php' => config_path('national-id.php'),
            ], 'laravel-egypt-national-id-parser-config');

            // Publish translations for Laravel 10+
            $this->publishes([
                __DIR__.'/../resources/lang' => base_path('lang/vendor/'),
            ], 'laravel-egypt-national-id-parser-translations');
        }

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-egypt-national-id-parser');
    }
}
