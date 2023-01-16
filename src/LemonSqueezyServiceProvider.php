<?php

namespace LaravelLemonSqueezy;

use Illuminate\Support\ServiceProvider;

class LemonSqueezyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/lemon-squeezy.php', 'lemon-squeezy'
        );
    }

    public function boot(): void
    {
        $this->bootMigrations();
        $this->bootPublishing();
    }

    protected function bootMigrations(): void
    {
        if (LemonSqueezy::$runsMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    protected function bootPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lemon-squeezy.php' => $this->app->configPath('lemon-squeezy.php'),
            ], 'lemon-squeezy-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'lemon-squeezy-migrations');
        }
    }
}
