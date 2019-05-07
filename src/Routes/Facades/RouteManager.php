<?php

namespace Douma\Routes\Facades;

use Douma\Routes\Contracts;

class RouteManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Contracts\RouteManager::class;
    }
}
