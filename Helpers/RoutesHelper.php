<?php

namespace Helpers;

class RoutesHelper
{
    private const GET = 'GET';

    public static function isGet(string $routeName): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== self::GET) {
            return false;
        }

        return self::is($routeName);
    }

    private static function is(string $routeName): bool
    {
        $routeName = $routeName[0] === '/' ? $routeName : '/' . $routeName;
        $currentRoute = $_SERVER['REQUEST_URI'];

        $pattern = '~^' . preg_replace('/{\w+}/', '([A-z0-9]+)', $routeName);
        $pattern .= '(\?([A-z0-9=_\-\&])*)*$~';

        return (bool)preg_match($pattern, $currentRoute);
    }
}