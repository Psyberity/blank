<?php
return new \Phalcon\Config([
    'domain' => 'blank.loc',
    'module_default' => 'www',
    'module_api' => 'api',
    'langs' => ['ru', 'en', 'fr', 'de'],
//    'db_adapter' => 'Postgresql',
//    'db_schema' => 'cms',
//    'database' => [
//        'host'        => 'localhost',
//        'username'    => 'postgres',
//        'password'    => '',
//        'dbname'      => 'blank',
//    ],
    'db_adapter' => 'Mysql',
    'database' => [
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => 'blank',
        'charset'     => 'utf8'
    ],
    'modules' => [
        'admin' => [
            'className' => 'Modules\Admin\AdminModule',
            'path' => '../app/modules/admin/Module.php',
            'subDomains' => ['admin']
        ],
        'api' => [
            'className' => 'Modules\Api\ApiModule',
            'path' => '../app/modules/api/Module.php',
            'subDomains' => ['api']
        ],
        'www' => [
            'className' => 'Modules\Www\WwwModule',
            'path' => '../app/modules/www/Module.php',
            'subDomains' => ['www']
        ]
    ]
]);