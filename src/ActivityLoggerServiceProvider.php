<?php

namespace DwiWijonarko\ActivityLogger;

use Illuminate\Support\ServiceProvider;

class ActivityLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/activity-logger.php',
            'activity-logger'
        );

        $this->app->singleton('activity-logger', function ($app) {
            return new ActivityLogger();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/config/activity-logger.php' => config_path('activity-logger.php'),
            ], 'activity-logger-config');

            // Publish migrations
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'activity-logger-migrations');
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
