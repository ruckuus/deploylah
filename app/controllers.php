<?php

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app->match('/', function() use ($app) {

    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/about', function() use ($app) {
    $aboutus = 'Lorem Ipsum er rett og slett dummytekst fra og for trykkeindustrien. Lorem Ipsum har vært bransjens standard for dummytekst helt siden 1500-tallet, da en ukjent boktrykker stokket en mengde bokstaver for å lage et prøveeksemplar av en bok. Lorem Ipsum har tålt tidens tann usedvanlig godt, og har i tillegg til å bestå gjennom fem århundrer også tålt spranget over til elektronisk typografi uten vesentlige endringer. Lorem Ipsum ble gjort allment kjent i 1960-årene ved lanseringen av Letraset-ark med avsnitt fra Lorem Ipsum, og senere med sideombrekkingsprogrammet Aldus PageMaker som tok i bruk nettopp Lorem Ipsum for dummytekst.';
    return $app['twig']->render('about.html.twig', array(
        'data' => $aboutus,
    ));
})->bind('about');

$app->match('/login', function(Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('username', 'text', array('label' => 'Username', 'data' => $app['session']->get('_security.last_username')))
        ->add('password', 'password', array('label' => 'Password'))
        ->getForm()
    ;

    return $app['twig']->render('login.html.twig', array(
        'form'  => $form->createView(),
        'error' => $app['security.last_error']($request),
    ));
})->bind('login');

$app->match('/logout', function() use ($app) {
    $app['session']->clear();

    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('logout');

$app->match('/repo', function(Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('github_username', 'text', array('label' => 'Github Username', 'data' => $app['session']->get('_security.last_username')))
        ->add('github_password', 'password', array('label' => 'Github Password'))
        ->getForm()
    ;

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $data['github_username'];
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

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;
