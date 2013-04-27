<?php

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app->mount('/', new Deploylah\Provider\IndexControllerProvider());
$app->mount('/user', new Deploylah\Provider\LoginControllerProvider());
$app->mount('/project', new Deploylah\Provider\ProjectControllerProvider());


$app->match('/repo', function(Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('github_username', 'text', array('label' => 'Github Username'))
        ->add('github_password', 'password', array('label' => 'Github Password'))
        ->getForm()
    ;

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $data['github_username'];
            $pass = $data['github_password'];

            $app['session']->set('github_user', array('username' => $user, 'password' => $pass));
            $app['github']->authenticate($user, $pass, Github\Client::AUTH_HTTP_PASSWORD);

            $repos = $app['github']->api('user')->repositories($user);
            return $app['twig']->render('repos.html.twig', array(
                'data' => $repos,
            ));
        }
    }
    
    return $app['twig']->render('repo.html.twig', array(
        'form' => $form->createView(),
        'error' => $app['security.last_error']($request),
    ));
})->bind('repo');

$app->post('/process', function(Request $request) use ($app) {
    if ('POST' == $request->getMethod()) {
        $reponame = $request->get('reponame');
        $github = $app['session']->get('github_user');

        /* In case we need to reauthenticate */
        $app['github']->authenticate($github['username'], $github['password'], Github\Client::AUTH_HTTP_PASSWORD);

        $commits = $app['github']->api('repo')->commits()->all($github['username'], $reponame, array('sha' => 'master'));

        return $app['twig']->render('commits.html.twig', array(
            'data' => $commits,
        ));
    }
});


$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
            $app['session']->getFlashBag()->add('error', $message);
    }

    return new Response($message, $code);
});

return $app;
