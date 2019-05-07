<?php

use Illuminate\Contracts\Config\Repository;

final class RouteMiddlewareTest extends \Tests\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_should_add_active_route_to_laravel_routing()
    {
        $routeManager = new \Douma\Routes\RouteManager\RouteManager();
        $route = new \Douma\Routes\Routes\Route(
            '/test', false, 'existing_route', 'MyController', 'index', []
        );
        $routeManager->addRoute($route);

        $sut = new \Douma\Routes\Middleware\RouteMiddleware($routeManager);
        $spy = \Illuminate\Support\Facades\Route::spy();
        $sut->handle($this->_getRequestObject(), function(){});

        $spy->shouldHaveReceived('any')
            ->withArgs(['/test', 'MyController@index'])
            ->once();
    }

    public function test_should_always_load_pattern_routes()
    {
        $routeManager = new \Douma\Routes\RouteManager\RouteManager();
        $route = new \Douma\Routes\Routes\Route(
            '/test', false, 'existing_route', 'MyController', 'index', []
        );
        $patternRoute = new \Douma\Routes\Routes\Route(
            '/test/{id}', true, 'existing_pattern_route_1', 'MyController', 'index', []
        );
        $patternRoute2 = new \Douma\Routes\Routes\Route(
            '/test2/{id}', true, 'existing_pattern_route_1', 'MyController', 'index', []
        );
        $routeManager->addRoute($route);
        $routeManager->addRoute($patternRoute);
        $routeManager->addRoute($patternRoute2);

        $sut = new \Douma\Routes\Middleware\RouteMiddleware($routeManager);
        $spy = \Illuminate\Support\Facades\Route::spy();
        $sut->handle($this->_getRequestObject(), function(){});

        $spy->shouldHaveReceived('any')
            ->withArgs(['/test', 'MyController@index'])
            ->once();

        $spy->shouldHaveReceived('any')
            ->withArgs(['/test/{id}', 'MyController@index'])
            ->once();

        $spy->shouldHaveReceived('any')
            ->withArgs(['/test2/{id}', 'MyController@index'])
            ->once();
    }

    private function _getRequestObject()
    {
        return new class extends \Illuminate\Http\Request
        {
            public function getPathInfo()
            {
                return '/test';
            }
        };
    }
}
