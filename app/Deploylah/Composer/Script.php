<?php

namespace Deploylah\Composer;

class Script
{
    public static function install()
    {
        chmod('cache', 0777);
        chmod('log', 0777);
        chmod('public/assets', 0777);
        chmod('console', 0500);
        /* exec('php console assetic:dump'); */
    }
}
