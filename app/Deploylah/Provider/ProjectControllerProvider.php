<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Deploylah\Project\ProjectProvider;

class ProjectControllerProvider implements ControllerProviderInterface
{
    private $user;
    private $name;

    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', function (Application $app) {
            
            $this->user = $app['session']->get('_security.last_username');
            $projects = ProjectProvider::getProjectByUsername($this->user);
            return $app['twig']->render('project.html.twig', array(
                'data' => $projects,
            ));
        });
        
        $controllers->match('/new', function (Application $app) {
            $this->user = $app['session']->get('_security.last_username');
            return $app['twig']->render('project.new.html.twig');
        });

        return $controllers;
    }
}
