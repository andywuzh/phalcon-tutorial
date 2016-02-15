<?php

use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Session\Adapter\Files as Session;

$di->set('url', function() use ($config) {
    $url = new UrlProvider();

    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->set('session', function() {
    $session = new Session();

    $session->start();

    return $session;
});