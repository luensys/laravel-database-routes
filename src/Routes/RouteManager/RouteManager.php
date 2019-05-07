<?php

namespace Douma\Routes\RouteManager;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Douma\Routes\Routes\Route;
use Douma\Routes\Contracts;

class RouteManager implements Contracts\RouteManager
{
    private $routes = [];

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function routeByUrl(string $url) : Route
    {
        foreach($this->routes as $route){
            if($route->url() == $url) {
                return $route;
            }
        }
        return Route::$NULL;
    }

    public function routeByName(string $name) : Route
    {
        foreach($this->routes as $route){
            if($route->name() == $name) {
                return $route;
            }
        }
        return Route::$NULL;
    }

    public function routesWithPattern() : array
    {
        $return = [];
        foreach($this->routes as $route){
            if($route->isPattern()) {
                $return[] = $route;
            }
        }
        return $return;
    }
}
