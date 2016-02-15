<?php

use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Config\Adapter\Ini as ConfigIni;

define('APP_PATH', realpath('..') . '/');

try {
    // create a DI
    $di = new FactoryDefault();

    // read the configuration
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');

    // registering a set of directories taken from the configuration file
    require APP_PATH . 'app/config/loader.php';

    // load application services
    require APP_PATH . 'app/config/services.php';

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
     echo 'Exception: ', $e->getMessage();
}
