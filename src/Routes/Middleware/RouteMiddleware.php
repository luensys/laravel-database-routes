<?php

namespace Douma\Routes\Middleware;

use Closure;
use Douma\Routes\RouteManager\DbRouteManagerProxy;
use Douma\Routes\Contracts\RouteManager;

class RouteMiddleware
{
    private $routeManager;

    public function __construct(RouteManager $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public function handle($request, Closure $next)
    {
        if($route = $this->routeManager->routeByUrl($request->getPathInfo()))
        {
            \Route::any($route->url(), $route->controller() . '@' . $route->action());
        }

        foreach($this->routeManager->routesWithPattern() as $route)
        {
            \Route::any($route->url(), $route->controller() . '@' . $route->action());
        }

        return $next($request);
    }
}
