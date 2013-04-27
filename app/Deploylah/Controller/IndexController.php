<?php

namespace Deploylah\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class IndexController implements ControllerProviderInterface
{
    public $form = 'Default form';

    public function index(Application $app) {
        return $app['twig']->render('index.html.twig', array());    
    }

    public function about(Application $app) {
        return $app['twig']->render('about.html.twig', array());    
    }

    public function connect(Application $app) {
        $index = $app['controllers_factory'];
        $index->match('/', 'Deploylah\Controller\IndexController::index')->bind('homepage');
    }
}
