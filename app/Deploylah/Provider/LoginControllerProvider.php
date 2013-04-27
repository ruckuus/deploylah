<?php

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->match('/', function() use ($app) {
           if (!$app['security']->isGranted('ROLE_USER')) {
                return $app->redirect('/user/login');     
           } else {
                /* Redirect to user's account page */
                return $app->redirect($app['url_generator']->generate('homepage'));
           } 
        });

        $controllers->match('/login', function(Request $request) use ($app) {
            $form = $app['form.factory']->createBuilder('form')
                    ->add('username', 'text', array('label' => 'Username', 'data' => $app['session']->get('_security.last_username')))
                    ->add('password', 'password', array('label' => 'Password'))
                    ->getForm()
                    ;

                    return $app['twig']->render('login.html.twig', array(
                        'form'  => $form->createView(),
                        'error' => $app['security.last_error']($request),
                        'last_username' => $app['session']->get('_security.last_username'),
                    ));
        })->bind('login');

        $controllers->match('/logout', function() use ($app) {
            $app['session']->clear();

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('logout');

        return $controllers;
    }
}
