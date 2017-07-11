<?php

namespace Swagger\LaraSwagger\Test;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase;
use Swagger\LaraSwagger\Providers\LumeSwaggerServiceProvider;
use Swagger\Annotations as SWG;

/**
 * Class Lumen application test
 *
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Test LaraSwagger API docs",
 *         description="Test LaraSwagger API docs"
 *     )
 * )
 *
 * @SWG\Get(
 *      tags={"Test"},
 *      path="/",
 *      summary="Test api",
 *      description="Test api",
 *      produces={"application/json"},
 *      @SWG\Response(
 *          response="200",
 *          description="OK"
 *      )
 * )
 */
class LumenApplicationTest extends TestCase
{

    /**
     * @var \Illuminate\Contracts\Console\Kernel
     */
    protected $consoleKernel;

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $app = new Application(realpath(__DIR__));

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Laravel\Lumen\Exceptions\Handler::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \Swagger\LaraSwagger\Test\ConsoleKernel::class
        );

        $this->consoleKernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $app->register(LumeSwaggerServiceProvider::class);

        return $app;
    }

    /**
     * Test swagger controller
     */
    public function testController()
    {
        $this->app['config']->set('lara-swagger.api.directories', [__FILE__]);
        $this->get('/swagger/api-docs');

        $this->assertResponseOk();

        $this->seeJson(
            [
                'swagger' => '2.0',
                'info' => [
                    'title' => 'Test LaraSwagger API docs',
                    'description' => 'Test LaraSwagger API docs',
                    'version' => '1.0',
                ],
                'host' => 'localhost',
                'schemes' => [
                    'http',
                    'https'
                ],
                'paths' => [
                    '/' => [
                        'get' => [
                            'tags' => [
                                'Test'
                            ],
                            'summary' => 'Test api',
                            'description' => 'Test api',
                            'produces' => [
                                'application/json'
                            ],
                            'responses' => [
                                '200' => [
                                    'description' => 'OK'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $expected = [
            'cache-control' => ['no-cache'],
            'content-type' => ['application/json'],
        ];

        $this->assertEquals($expected, $this->response->headers->all());
    }

    /**
     * Test cors enabled
     */
    public function testControllerWithCors()
    {
        $this->app['config']->set('lara-swagger.api.directories', [__FILE__]);
        $this->app['config']->set('lara-swagger.routes.cors', true);
        $this->get('/swagger/api-docs');

        $expected = [
            'cache-control' => ['no-cache'],
            'content-type' => ['application/json'],
            'access-control-allow-methods' => ['GET'],
            'access-control-allow-headers' => ['Content-Type'],
            'access-control-allow-origin' => ['*'],
        ];

        $this->assertEquals($expected, $this->response->headers->all());
    }

    /**
     * Test generate docs command
     */
    public function testGeneratorDocsCommand()
    {
        $output = '/tmp/swagger.json';
        if (file_exists($output)) {
            unlink($output);
        }
        $this->app['config']->set('lara-swagger.api.directories', [__FILE__]);

        $this->consoleKernel->call('swagger:generate', ['file_name' => $output, 'base_host' => 'test.local']);

        $expected = <<<JSON
            {
                "swagger": "2.0",
                "info": {
                    "title": "Test LaraSwagger API docs",
                    "description": "Test LaraSwagger API docs",
                    "version": "1.0"
                },
                "schemes": [
                    "http",
                    "https"
                ],
                "host": "test.local",
                "paths": {
                    "/": {
                        "get": {
                            "tags": [
                                "Test"
                            ],
                            "summary": "Test api",
                            "description": "Test api",
                            "produces": [
                                "application/json"
                            ],
                            "responses": {
                                "200": {
                                    "description": "OK"
                                }
                            }
                        }
                    }
                },
                "definitions": {}
            }
JSON;

        $this->assertJsonStringEqualsJsonString(
            $expected,
            file_get_contents($output),
            'Generated api doc is not correct.'
        );

        unlink($output);
    }

}
