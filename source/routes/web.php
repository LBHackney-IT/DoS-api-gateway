<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get(
    '/',
    function () use ($router) {
        return $router->getRoutes();
    }
);

$router->group(
    ['namespace' => 'Events', 'prefix' => 'event'],
    function () use ($router) {
        $router->group(
            ['namespace' => 'Scraper', 'prefix' => 'scraper'],
            function () use ($router) {
                $router->group(
                    ['prefix' => 'web'],
                    function () use ($router) {
                        $router->get(
                            'icare',
                            ['uses' => 'WebScraperController@iCareHello']
                        );
//                        $router->get(
//                            'icare/{id}',
//                            ['uses' => 'WebScraperController@iCareService']
//                        );
                    }
                );
            }
        );
        $router->group(
            ['namespace' => 'Storage', 'prefix' => 'storage'],
            function () use ($router) {
                $router->post(
                    'test',
                    ['uses' => 'StorageController@test']
                );
                $router->post(
                    'provider',
                    ['uses' => 'StorageController@provider']
                );
                $router->put(
                    'provider/{id}',
                    ['uses' => 'StorageController@providerUpdate']
                );
            }
        );
    }
);
