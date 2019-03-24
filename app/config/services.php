<?php
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Session\Adapter\Files as SessionAdapter;

$di = new FactoryDefault();

$di->setShared('config', function() use ($config) {
    return $config;
});

$di->set('url', function() use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

$di->set('router', function() {
    return require __DIR__ . '/routes.php';
});

$di->set('view', function() use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);
            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            $compiler = $volt->getCompiler();
            $compiler->addFilter('getAttribute', function($resolvedArgs, $exprArgs) {
            return vsprintf('%s->{%s}', explode(', ', $resolvedArgs));
            });
            $compiler->addFunction('strtotime', 'strtotime');
            $compiler->addFunction('array_merge', 'array_merge');
            $compiler->addFunction('date', 'date');
            $compiler->addFunction('floor', 'floor');
            $compiler->addFunction('print_r', 'print_r');
            $compiler->addFunction('unserialize', 'unserialize');

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);

    return $view;
}, true);

$di->set('db', function() use ($config) {
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        "charset" => $config->database->charset
    ]);
});

$di->set(
    'dispatcher',
    function() use ($di) {

        $evManager = $di->getShared('eventsManager');

        $evManager->attach(
            "dispatch:beforeException",
            function($event, $dispatcher, $exception) {
                switch ($exception->getCode()) {
                    case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward([
                            'controller' => 'error',
                            'action'     => 'show404'
                        ]);
                        return false;
                }
            }
        );
        $dispatcher = new PhDispatcher();
        $dispatcher->setDefaultNamespace('App\Controllers');
        $dispatcher->setEventsManager($evManager);
        return $dispatcher;
    },
    true
);

$di->set('modelsMetadata', function() {
    return new MetaDataAdapter();
});

$di->set('session', function() {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});