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
        'modulesDir'        => __DIR__ . '/../../',
        'traitsDir'         => __DIR__ . '/../../../traits/',
        'interfacesDir'     => __DIR__ . '/../../../interfaces/',
        'baseUri'           => '/',
        'cryptSalt'         => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'anonymous_role_id' => 2
    ],
    'namespaces' => [
        'Modules\Admin\Controllers'     => 'controllersDir',
        'Modules\Admin\Forms'           => 'formsDir',
        'Modules\Admin\Library'         => 'libraryDir',
        'App\Models'                    => 'modelsDir',
        'App\Classes'                   => 'classesDir',
        'App\Traits'                    => 'traitsDir',
        'App\Interfaces'                => 'interfacesDir',
        'Modules'                       => 'modulesDir'
    ]
]);