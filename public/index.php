<?php

ini_set('display_errors', 0);

require_once dirname(__DIR__) . '/app/bootstrap.php';
require dirname(__DIR__) . '/app/app.php';

/* FTM just put it this way, later I'm gonna steal VGMdb's RoutingServiceProvider */
require dirname(__DIR__) . '/app/controllers.php';
//require dirname(__DIR__) . '/app/routes.php';

$app['http_cache']->run();
