<?php

namespace Douma\Routes\ServiceProviders;

use Douma\Routes\Contracts;
use Douma\Routes\Routes\NullRoute;
use Douma\Routes\Routes\Route;
use Douma\Routes\RouteManager\DbRouteManagerProxy;

class DbServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Route::$NULL = NullRoute::invoke();
        app()->bind(Contracts\RouteManager::class, DbRouteManagerProxy::class);
    }
}
