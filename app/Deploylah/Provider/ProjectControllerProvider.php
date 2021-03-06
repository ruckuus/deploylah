<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Deploylah\Model\Project;
use Deploylah\Model\User;

class ProjectControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            /* I haven't worked on Models, then I use this dumb shortcut*/
            $username = $app['session']->get('_security.last_username');
            $id = User::find_by_username($username);
            $projects = Project::find_by_user_id($id->alias_id); 

            $data = $projects ? $projects->serialize() : 'You don\'t have any project';

            return $app['twig']->render('project_list.html.twig', array(
                'data' => $data
            ));

        })->bind('project');

        $controllers->match('/new', function(Request $request) use ($app) {
            $form = $app['form.factory']->createBuilder('form')
                    ->add('project_name', 'text', array('label' => 'Name'))
                    ->getForm()
                    ;

            if ('POST' === $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();
                    $project_attrs = array( 
                        'name' => $data['project_name'],
                        'user_id' => $app['session']->get('user_id') ?
                                     $app['session']->get('user_id') : 1
                    );

                    Project::create($project_attrs);
                    
                    return $app->redirect('/project');
                }
            }

            return $app['twig']->render('project_new.html.twig', array(
                'form' => $form->createView()
            ));

        })->bind('project_new');

        return $controllers;
    }
}
