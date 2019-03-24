<?php
error_reporting(E_ALL);
#error_reporting(0);

use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$config = include __DIR__ . '/../app/config/config.php';

//if ($config->application->debug == true) {
	//$debug = new \Phalcon\Debug();
	//$debug->listen();
//}

try {
    $di = new FactoryDefault();
} catch( Exception $e ){
    echo '123'; die;
}
$di->set('router', function () use ($config) {
	return include '../app/router.php';
}, true);

$di->set('db', function () use ($config) {
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset
    ]);
});

$application = new \Phalcon\Mvc\Application();
$application->setDI($di);

$register_data = [];
foreach($config->modules as $module_name => $module_data) {
    $register_data[$module_name] = [
        'className' => $module_data['className'],
        'path' => $module_data['path']
    ];
}
$application->registerModules($register_data);

echo $application->handle()->getContent();

function p($data, $die = true) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($die) die;
}