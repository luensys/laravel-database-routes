<?php

namespace Douma\Routes\RouteManager;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Douma\Routes\Contracts;
use Douma\Routes\Routes\Route;

class DbRouteManagerProxy implements Contracts\RouteManager
{
    private $routes = [];

    public function addRoute(Route $route)
    {
        \DB::statement("REPLACE INTO routes (url, name, is_pattern, controller, action) 
            VALUES(?, ?, ?, ?, ?)",[
                $route->url(),
                $route->name(),
                $route->isPattern(),
                $route->controller(),
                $route->action()
        ]);
    }

    public function routeByUrl(string $url) : Route
    {
        $select = \DB::select("SELECT * FROM routes WHERE url = ?", [$url]);
        if(isset($select[0])) {
            return new Route(
                $select[0]->url,
                (bool) $select[0]->is_pattern,
                $select[0]->name,
                $select[0]->controller,
                $select[0]->action,
                []
            );
        }
        return Route::$NULL;
    }

    public function routeByName(string $name) : Route
    {
        $select = \DB::select("SELECT * FROM routes WHERE name = ?", [$name]);
        if(isset($select[0])) {
            return new Route(
                $select[0]->url,
                (bool) $select[0]->is_pattern,
                $select[0]->name,
                $select[0]->controller,
                $select[0]->action,
                []
            );
        }
        return Route::$NULL;
    }

    public function routesWithPattern() : array
    {
        $return = [];
        $select = \DB::select("SELECT * FROM routes WHERE is_pattern = 1");
        foreach($select as $item) {
            $return[] = new Route(
                $select[0]->url,
                (bool) $select[0]->is_pattern,
                $select[0]->name,
                $select[0]->controller,
                $select[0]->action,
                []
            );
        }
        return $return;
    }
}
