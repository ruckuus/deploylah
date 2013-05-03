<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Deploylah\Model\Project;
use Deploylah\Model\User;

class ProjectControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            $username = $app['session']->get('_security.last_username');
            $id = User::find_by_username($username);
            $projects = Project::find_by_user_id($me); 

            $data = $projects ? $projects->serialize() : 'You don\'t have any project';

            return $app['twig']->render('project_list.html.twig', array(
                'data' => $data
            ));
        })->bind('project');

        $controllers->match('/new', function(Application $app) {
            $form = $app['form.factory']->createBuilder('form')
                    ->add('project_name', 'text', array('label' => 'Name'))
                    ->getForm()
                    ;

            return $app['twig']->render('project_new.html.twig', array(
                'form' => $form->createView()
            ));
        })->bind('project_new');

        return $controllers;
    }
}
