<?php
use Phalcon\Mvc\Router;

$router = new Router(false);
$host = explode('.', $_SERVER['HTTP_HOST']);

$lang = $config->langs[0];
$first_sub = (count($host) < 3) ? false : $host[0];
if ($first_sub && in_array($first_sub, (array)$config->langs)) {
    $lang = $first_sub;
    array_shift($host);
}

$subdomain = (count($host) < 3) ? false : $host[0];

$module = $config->module_default;
if ($subdomain !== false) {
    foreach ($config->modules as $module_name => $module_data) {
        if (in_array($subdomain, (array)$module_data['subDomains'])) {
            $module = $module_name;
            break;
        }
    }
}

require_once 'modules/' . $module . '/routes.php';
require_once 'modules/ModuleBase.php';
$router_class = '\Modules\\' . ucfirst($module) . '\Config\\' . ucfirst($module) . 'Routers';
$router->mount(new $router_class($lang));

$router->removeExtraSlashes(true);
return $router;