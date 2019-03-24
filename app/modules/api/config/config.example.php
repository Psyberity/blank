<?php

return new \Phalcon\Config([
    'application' => [
        'debug'			    => false,
        'controllersDir'    => __DIR__ . '/../controllers/',
        'modelsDir'         => __DIR__ . '/../../../models/',
        'formsDir'	        => __DIR__ . '/../forms/',
        'classesDir'	    => __DIR__ . '/../../../classes/',
        'viewsDir'          => __DIR__ . '/../views/',
        'pluginsDir'        => __DIR__ . '/../plugins/',
        'libraryDir'        => __DIR__ . '/../library/',
        'cacheDir'          => __DIR__ . '/../cache/',
        'baseUri'           => '/',
        'cryptSalt'         => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'anonymous_role_id' => 4
    ],
    'namespaces' => [
        'Modules\Api\Controllers'     => 'controllersDir',
        'Modules\Api\Forms'           => 'formsDir',
        'Modules\Api\Library'         => 'libraryDir',
        'App\Models'                  => 'modelsDir',
        'App\Classes'                 => 'classesDir'
    ]
]);