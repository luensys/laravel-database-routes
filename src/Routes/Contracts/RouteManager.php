<?php

namespace Douma\Routes\Contracts;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Douma\Routes\Routes\Route;

interface RouteManager
{
    public function addRoute(Route $route);
    public function routeByUrl(string $url) : Route;
    public function routeByName(string $name) : Route;
    public function routesWithPattern() : array;
}
