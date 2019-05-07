<?php

namespace Douma\Routes\Routes;

use App\Controllers\PageController;

class NullRoute extends Route
{
    public static function invoke()
    {
        return new self(
            "#", false, "null-route", "", "", []
        );
    }
}
