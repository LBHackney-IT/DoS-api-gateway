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
                    }
                );
            }
        );
    }
);
$router->group(
    ['prefix' => 'provider'],
    function () use ($router) {
        $router->post(
            '/',
            ['uses' => 'ProviderController@create']
        );
        $router->get(
            '/',
            [
                'uses' => 'ProviderController@getIndex',
                'as' => 'listProvider',
            ]
        );
        $router->put(
            '/{id}',
            ['uses' => 'ProviderController@update']
        );
        $router->get(
            '/{id}',
            [
                'uses' => 'ProviderController@get',
                'as' => 'getProvider',
            ]
        );
        $router->delete(
            '/{id}',
            ['uses' => 'ProviderController@delete']
        );
    }
);
