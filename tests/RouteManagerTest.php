<?php

use Illuminate\Contracts\Config\Repository;

final class RouteManagerTest extends \Tests\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \Douma\Routes\Routes\Route::$NULL = \Douma\Routes\Routes\NullRoute::invoke();
    }

    public function test_byName_should_return_null_object_for_non_existing_route()
    {
        $sut = new \Douma\Routes\RouteManager\RouteManager();
        $route = $sut->routeByName('non_existing');
        $this->assertInstanceOf(\Douma\Routes\Routes\NullRoute::class, $route);
    }

    public function test_byName_should_return_route_for_existing_route()
    {
        $sut = new \Douma\Routes\RouteManager\RouteManager();
        $route = new \Douma\Routes\Routes\Route(
            '/test', false, 'existing_route', 'MyController', 'index', []
        );
        $sut->addRoute($route);
        $routeRequest = $sut->routeByName('existing_route');
        $this->assertEquals($route,  $routeRequest);
    }

    public function test_byUrl_should_return_null_object_for_non_existing_route()
    {
        $sut = new \Douma\Routes\RouteManager\RouteManager();
        $route = $sut->routeByUrl('/non_existing');
        $this->assertInstanceOf(\Douma\Routes\Routes\NullRoute::class, $route);
    }

    public function test_byUrl_should_return_route_for_existing_route()
    {
        $sut = new \Douma\Routes\RouteManager\RouteManager();
        $route = new \Douma\Routes\Routes\Route(
            '/test', false, 'existing_route', 'MyController', 'index', []
        );
        $sut->addRoute($route);
        $routeRequest = $sut->routeByUrl('/test');
        $this->assertEquals($route,  $routeRequest);
    }

    public function test_should_return_routes_with_pattern()
    {
        $routeWithoutPattern = new \Douma\Routes\Routes\Route(
            '/test', false, 'existing_route', 'MyController', 'index', []
        );
        $routeWithPattern = new \Douma\Routes\Routes\Route(
            '/test/{id}', true, 'existing_route', 'MyController', 'index', []
        );

        $sut = new \Douma\Routes\RouteManager\RouteManager();
        $sut->addRoute($routeWithPattern);
        $sut->addRoute($routeWithoutPattern);

        $routes = $sut->routesWithPattern();
        $this->assertCount(1, $routes);
        $this->assertEquals($routeWithPattern, $routes[0]);
    }
}
