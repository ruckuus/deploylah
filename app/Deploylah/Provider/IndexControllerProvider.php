<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class IndexControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('index.html.twig');
        })->bind('homepage');

        $controllers->match('/about', function() use ($app) {
            $aboutus = 'LOLOLOLOL';
            return $app['twig']->render('about.html.twig', array(
                'data' => $aboutus,
            ));
        })->bind('about');

        return $controllers;
    }
}
