<?php

namespace Swagger\LaraSwagger\Providers;

use Illuminate\Routing\Router;
use Swagger\LaraSwagger\Http\Controllers\SwaggerController;

/**
 * Integrate LaraSwagger into a Laravel application.
 */
class LaravelSwaggerServiceProvider extends SwaggerServiceProvider
{
    protected function route()
    {
        /**
         * @var $router Router
         */
        $router = $this->app->make(Router::class);
        $router->group(
            [
                'prefix'    => config('swaggeravel.routes.prefix'),
            ],
            function (Router $app) {
                $app->get('/api-docs', SwaggerController::class.'@index');
            }
        );
    }

    /**
     * Configure
     *
     * @return void
     */
    protected function configure()
    {
    }
}
