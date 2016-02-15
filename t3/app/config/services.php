<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;

$di->set('url', function() use ($config) {
    $url = new UrlProvider();

    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->set('db', function() use ($config) {
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
    ]);
});

$di->set('view', function () use ($config) {
    $view = new View();
    $view->setViewsDir(APP_PATH . $config->application->viewsDir);
    return $view;
});


$di->set('dispatch', function () {
    $eventsManager = new EventsManager();

    $eventsManager->attach('dispatch:beforeExecuteRoute', new SecurityPlugin);

    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});