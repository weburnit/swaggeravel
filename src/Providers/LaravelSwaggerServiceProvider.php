<?php

namespace Swagger\LaraSwagger\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Swagger\LaraSwagger\Http\Controllers\SwaggerController;

/**
 * Integrate LaraSwagger into a Laravel application.
 */
class LaravelSwaggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * @var $router Router
         */
        $router = $this->app->make(Router::class);
        $router->group(
            [
                'namespace' => 'Swagger\LaraSwagger\Http\Controllers',
                'prefix'    => config('swaggeravel.routes.prefix'),
            ],
            function (Router $app) {
                $app->get('/api-docs', SwaggerController::class.'@index');
            }
        );
    }
}
