<?php

namespace Qh\LaravelSnowflake;

use Illuminate\Support\ServiceProvider;

class SnowflakeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Snowflake::class);
    }
}
