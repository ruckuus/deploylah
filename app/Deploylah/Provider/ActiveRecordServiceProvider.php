<?php

/*
 * Author: Dwi Sasongko S <ruckuus@gmail.com>
 */

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ActiveRecordServiceProvider implements ServiceProviderInterface
{
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;

        $connections = array(
                    'development' => 'mysql://root:secret@localhost/deploylah',
                    'integration' => 'mysql://root:secret@localhost/deploylah',
                    'production' => 'mysql://root:secret@localhost/deploylah',
        );

        \ActiveRecord\Config::initialize(function ($cfg) use ($app) {
                $cfg->set_model_directory($app['base_dir'] . '/app/' . $app['name'] . '/Model');
                $cfg->set_connections(array(
                    'development' => 'mysql://root@localhost/deploylah',
                ));
                $cfg->set_default_connection('development');
            });
    }

    public function boot(Application $app)
    {}
}
