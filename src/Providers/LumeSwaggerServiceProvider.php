<?php

namespace Swagger\LaraSwagger\Providers;

use Laravel\Lumen\Application;

/**
 * Integrate LaraSwagger into a Lumen application.
 *
 * @property Application $app Lumen application.
 */
class LumeSwaggerServiceProvider extends SwaggerServiceProvider
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this->app->config(__DIR__.'/../../config/swaggeravel.php', 'swaggeravel');
        $this->app->configure('swaggeravel');
    }

    /**
     * Route
     */
    protected function route()
    {
        $this->app->group(
            [
                'namespace' => 'Swagger\LaraSwagger\Http\Controllers',
                'prefix'    => config('swaggeravel.routes.prefix'),
            ],
            function (Application $app) {
                $app->get('/api-docs', ['as' => 'swagger-ai-docs', 'uses' => 'SwaggerController@index']);
            }
        );
    }
}
