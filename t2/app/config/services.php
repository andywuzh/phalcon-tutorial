<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;

$di->set('url', function() use ($config) {
    $url = new UrlProvider();

    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->set('view', function () use ($config) {
    $view = new View();
    $view->setViewsDir(APP_PATH . $config->application->viewsDir);
    return $view;
});