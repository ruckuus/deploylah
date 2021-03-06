<?php

/*
 * Author: Dwi Sasongko S <ruckuus@gmail.com>
 */

namespace Deploylah\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Github\Client;
use Github\Exception;

class GithubServiceProvider implements ServiceProviderInterface
{
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;
        $app['github'] = $app->share(function ($app) {
            $github = new Github\Client(
                        new Github\HttpClient\CachedHttpClient(array(
                            'cache_dir' => $app['cache.path'] . '/github/github-api-cache'
                            )
                        )
                    );

            return $github;
        });
    }

    public function boot(Application $app)
    {}
}
