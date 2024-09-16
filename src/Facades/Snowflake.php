<?php

namespace Qh\LaravelSnowflake\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Qh\LaravelSnowflake\Snowflake
 */
class Snowflake extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Qh\LaravelSnowflake\Snowflake::class;
    }
}
