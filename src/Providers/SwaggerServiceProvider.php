<?php

namespace Swagger\LaraSwagger\Providers;

use Illuminate\Support\ServiceProvider;
use Swagger\LaraSwagger\Swagger\SwaggerOption;
use Swagger\LaraSwagger\Swagger\SwaggerGenerator;
use Swagger\LaraSwagger\Console\Commands\GenerateDocsCommand;

/**
 * Swagger service provider.
 */
abstract class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Configure
     *
     * @return void
     */
    abstract protected function configure();

    /**
     * Route
     *
     * @return void
     */
     abstract protected function route();

    /**
     * boot process
     */
    public function boot()
    {
        $this->configure();
        $this->route();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/swaggeravel.php', 'swaggeravel');

        $this->app->singleton(SwaggerOption::class, function() {
            return new SwaggerOption(
                config('swaggeravel.api.directories'),
                config('swaggeravel.api.excludes'),
                config('swaggeravel.api.host')
            );
        });
        $this->app->singleton(SwaggerGenerator::class, SwaggerGenerator::class);
        $this->app->singleton(GenerateDocsCommand::class, GenerateDocsCommand::class);

        $this->commands(GenerateDocsCommand::class);
    }
}
