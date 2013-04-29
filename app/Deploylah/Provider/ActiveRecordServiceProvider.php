<?php

/*
 * Author: Dwi Sasongko S <ruckuus@gmail.com>
 */

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use ActiveRecord;
use ActiveRecord\Config;

class ServiceProvider implements ServiceProviderInterface
{
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;
        $app['ar'] = $app->share(function ($app) {
            ActiveRecord\Config::initialize(function ($config) {
                $config->set_model_directory($app['base_dir'] . '/' . $app['name'] . '/Model');
                $config->set_connection(array(
                    $app['env'] => $app['ar.dsn']
                ));
            });
        });
    }

    public function boot(Application $app)
    {}
}
