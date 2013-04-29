<?php

/**
 * app.php, stolen from Kitchen Sink
 */
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Silex\Application(array(
    'name' => 'Deploylah',
    'base_dir' => dirname(__DIR__),
    'log_dir' => dirname(__DIR__) . '/log',
    'locale' => 'en',
    'session.default_locale' => 'en',
    'translator.messages' => array(
        'en' => dirname(__DIR__) . '/resources/locales/en.yml',
    ),
    'cache.path' => dirname(__DIR__) . '/cache',
    'env' => getenv('APP_ENV') ?: 'prod',
));

$app->register(new Igorw\Silex\ConfigServiceProvider($app['base_dir'] . '/resources/config/' . $app['env'] . '.json'));

$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

$app->register(new HttpCacheServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/',
            'form'    => array(
                'login_path'         => '/login',
                'username_parameter' => 'form[username]',
                'password_parameter' => 'form[password]',
                'check_path' => '/admin/login_check',
            ),
            'logout'    => true,
            'anonymous' => true,
            'users'     => $app->share(function() use ($app) {
                return new Deploylah\User\UserProvider($app['db']);
            }),
        ),
    ),
));

$app->register(new TranslationServiceProvider());

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', $app['base_dir'] . '/resources/locales/en.yml', 'en');

    return $translator;
}));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => $app['log_dir'] . '/' . $app['name'] . '-log',
    'monolog.name'    => $app['name'],
    'monolog.level'   => 300 // = Logger::WARNING
));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
    'twig.path'           => array($app['base_dir'] . '/resources/views')
));

if ($app->offsetExists('doctrine.options')) {
    $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
        'db.options' => $app['doctrine.options']
    ));
}

/* Github service provider */
$app->register(new Deploylah\Provider\GithubServiceProvider());

/* ActiveRecord */
$app->register(new Deploylah\Provider\ActiveRecordServiceProvider());

return $app;
