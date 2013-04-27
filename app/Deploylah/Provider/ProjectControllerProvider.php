<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ProjectControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', function (Application $app) {
            return $app->redirect('/about');
        });

        return $controllers;
    }
}
