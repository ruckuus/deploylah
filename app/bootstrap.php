<?php

if (!file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    die('<strong>You need to run `composer install --dev`</strong>');
}

$autoloader = require(dirname(__DIR__) . '/vendor/autoload.php');
$autoloader->add('\\Deploylah\\Provider\\', __DIR__ . '/Deploylah/Provider');

return $autoloader;
