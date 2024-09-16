# Laravel Snowflake Id Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/qh-8/laravel-snowflake.svg?style=flat-square)](https://packagist.org/packages/qh-8/laravel-snowflake)
[![Total Downloads](https://img.shields.io/packagist/dt/qh-8/laravel-snowflake.svg?style=flat-square)](https://packagist.org/packages/qh-8/laravel-snowflake)
![GitHub Actions](https://github.com/qh-8/laravel-snowflake/actions/workflows/tests.yml/badge.svg)


A Laravel package to generate unique snowflake ids.

Forked from [kra8/laravel-snowflake](https://github.com/kra8/laravel-snowflake).

## Requirements

- Laravel 10 or higher

## Installation

```bash
composer require qh-8/laravel-snowflake
```

## Usage

Using the `Snowflake` class to generate and decode snowflake ids.

```php
use Qh\LaravelSnowflake\Snowflake;

$snowflake = app(Snowflake::class)->generate(); // or via Facade: Snowflake::generate()

// 93977444276639021

$data = app(Snowflake::class)->decode(93977444276639021); // or via Facade: Snowflake::decode(int $id)

//[
//  'binary_length' => 57,
//  'binary' => '101001101110111111111110011010111000000100001010100101101',
//  'binary_timestamp' => '10100110111011111111111001101011100',
//  'binary_sequence' => '010100101101',
//  'binary_worker_id' => '00001',
//  'binary_datacenter_id' => '00001',
//  'timestamp' => 22405968732,
//  'sequence' => 1325,
//  'worker_id' => 1,
//  'datacenter_id' => 1,
//  'epoch' => 1704067200000,
//  'datetime' => '2024-09-16T07:52:48+00:00',
//]
```

Using in the eloquent model:

```php
use \Qh\LaravelSnowflake\HasSnowflakeIds;

class User extends Model
{
    use HasSnowflakeIds;
    
    //
}
```

Please note that the `id` column in the table should be a `BIGINT` type.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email dqh@dinhquochan.com instead of using the issue tracker.

## Credits

-   [Dinh Quoc Han](https://github.com/dinhquochan)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
