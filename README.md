# Swagger/LaraSwagger

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
==========
This package is a wrapper of [Swagger-php](https://github.com/zircote/swagger-php) and makes it easy to integrate with Lumen/Larvarel.

## Usage

### Installation
Via Composer

Add information about new package in your `composer.json`
```json
    "repositories": [
        {
          "type": "vcs",
          "url": "git@gitlab.lzd.co:operations/laraswagger.git",
          "name": "lazada/database-minifier"
        }
    ],
    "require": {
        "lazada/laraswagger": ">=1.0.0"
    }
```

After the composer install finishes, register the service provider:

 * Lumen Application:

```php
$app->register(Swagger\LaraSwagger\Providers\LumeSwaggerServiceProvider::class);
```

 * Laravel Application: not supports yet.

Now you can rock with LaraSwagger:
* Run `php artisan swagger:generate file_name [base_host]`: to generate swagger api docs.

* Go to `/swagger/api-docs` (default routing config) to see swagger api docs in JSON format

### Default configuration
```php
<?php
return [
    'routes' => [
        'prefix' => 'swagger'
    ],
    'api' => [
        'directories' => [base_path('app')],
        'excludes' => [],
        'host' => null
    ]
];
```

### Customize configuration
In order to change default config you can copy the configuration template in `config/lara-swagger.php` to your application's `config` directory and modify according to your needs.
For more information see the [Configuration Files](http://lumen.laravel.com/docs/configuration#configuration-files) section in the Lumen documentation.

## Tests
```sh
./vendor/phpunit/phpunit/phpunit
```
See test result at `./build`
