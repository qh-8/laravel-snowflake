<?php

namespace Qh\LaravelSnowflake;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SnowflakeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Snowflake::class, function (Application $app): Snowflake {
            $config = $app->make('config');

            return new Snowflake(
                (int) $config->get('snowflake.epoch'),
                (int) $config->get('snowflake.worker_id', 1),
                (int) $config->get('snowflake.datacenter_id', 1),
            );
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/snowflake.php',
            'snowflake',
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/snowflake.php' => config_path('snowflake.php'),
        ], 'snowflake-config');
    }
}
