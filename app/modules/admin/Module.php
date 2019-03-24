<?php
namespace Modules\Admin;

use Modules\ModuleBase;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class AdminModule extends ModuleBase
{
    protected $namespaces;
    protected $module_dir = 'admin';
    protected $config;

    public function registerAutoloaders()
    {
        $api_module_config = include __DIR__ . '/../api/config/config.php';
        $this->setNamespaces($api_module_config);
		$loader = new Loader();
        $loader->registerNamespaces($this->namespaces);

        // TODO: подключить сюда classes/Auth
        /*$loader->registerClasses([
            'PHP_ICO' => $this->config->application->libraryDir . 'PHP_ICO/class-php-ico.php'
        ]);*/

		$loader->register();
	}

    public function registerServices($di)
    {
        $config = include __DIR__ . '/../../config/config.php';
        $config_module = include __DIR__ . '/config/config.php';
        $config_assets = include __DIR__ . '/config/config_assets.php';
        $config->merge($config_module);
        $config->merge($config_assets);

		$di->setShared('config', function () use ($config) {
			return $config;
		});

		$di->set('url', function () use ($config) {
			$url = new UrlResolver();
			$url->setBaseUri($config->application->baseUri);

			return $url;
		}, true);

		$di->set('view', function () use ($config) {

			$view = new View();
			$view->setViewsDir($config->application->viewsDir);
			$view->registerEngines([
				'.volt' => function ($view, $di) use ($config) {

					$volt = new VoltEngine($view, $di);
					$volt->setOptions([
						'compiledPath' => $config->application->cacheDir,
						'compiledSeparator' => '_'
					]);

					$compiler = $volt->getCompiler();
					$compiler->addFilter('getAttribute', function ($resolvedArgs, $exprArgs) {
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

		$di->set(
			'dispatcher',
			function() use ($di) {

				$evManager = $di->getShared('eventsManager');

				$evManager->attach(
					"dispatch:beforeException",
					function($event, $dispatcher, $exception)
					{
						switch ($exception->getCode()) {
							case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
							case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
								$dispatcher->forward([
                                    'controller' => 'error',
                                    'action'     => 'show404',
								]);
								return false;
						}
					}
				);
				$dispatcher = new PhDispatcher();
				$dispatcher->setDefaultNamespace('Modules\Admin\Controllers');
				$dispatcher->setEventsManager($evManager);
				return $dispatcher;
			},
			true
		);

		$di->set('session', function () {
			$session = new SessionAdapter();
			$session->start();

			return $session;
		});
	}
}