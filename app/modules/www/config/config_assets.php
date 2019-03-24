<?php
return new \Phalcon\Config([
    'assets' => [
        'js' => [
            'jquery_min' => '/plugins/jquery/jquery-3.1.1.min.js',
            'jquery_ui_min' => '/plugins/jquery-ui/jquery-ui.min.js',
			'toastr_min' => '/plugins/toastr/toastr.min.js',
			'www_main' => '/js/main.js'
        ],
        'css' => [
			'toastr_min' => '/plugins/toastr/toastr.min.css',
            'www_style' => '/css/style.css'
        ]
    ],
    'asset_sets' => [
        'js' => [
            '_all' => [
                'jquery_min',
                'jquery_ui_min',
				'toastr_min',
				'www_main'
            ]
        ],
        'css' => [
            '_all' => [
				'toastr_min',
                'www_style'
            ]
        ]
    ]
]);