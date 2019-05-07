<?php

namespace Douma\Routes\ServiceProviders;

use Douma\Routes\Contracts;
use Douma\Routes\NullRoute;
use Douma\Routes\Route;
use Douma\Routes\RouteManager\RouteManager;

class InMemoryServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Route::$NULL = NullRoute::invoke();
        app()->bind(Contracts\RouteManager::class, RouteManager::class);
    }
}
