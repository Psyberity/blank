<?php
return new \Phalcon\Config([
    'assets' => [
        'js' => [
            'jquery_min' => '/plugins/jquery/jquery-3.1.1.min.js',
            'bootstrap_min' => '/plugins/bootstrap/bootstrap.min.js',
            'metisMenu' => '/plugins/jquery/jquery.metisMenu.js',
            'slimscroll_min' => '/plugins/jquery/jquery.slimscroll.min.js',
            'inspinia' => '/plugins/inspinia/inspinia.js',
            'pace_min' => '/plugins/pace/pace.min.js',
            'jquery_ui_min' => '/plugins/jquery-ui/jquery-ui.min.js',
            'toastr_min' => '/plugins/toastr/toastr.min.js',
            'admin_main' => '/js/main.js',

            'datatables_min' => '/plugins/datatables/datatables.min.js',
            'datatables_init' => '/js/datatables-init.js',

            'bootstrap_datepicker' => '/plugins/datapicker/bootstrap-datepicker.js',
            'chosen_jquery' => '/plugins/chosen/chosen.jquery.js',
            'summernote_min' => '/plugins/summernote/summernote.min.js',
            'jquery_validate_min' => '/plugins/validate/jquery.validate.min.js',
            'additional_methods_min' => '/plugins/validate/additional-methods.min.js',
            'select2' => '/plugins/select2/select2.js',

            'module_user_create' => '/js/scripts/module-user-create.js',
            'module_user_edit' => '/js/scripts/module-user-edit.js'
        ],
        'css' => [
            'bootstrap_min' => '/plugins/bootstrap/bootstrap.min.css',
            'font_awesome' => '/plugins/font-awesome/css/font-awesome.css',
            'toastr_min' => '/plugins/toastr/toastr.min.css',
            'admin_animate' => '/css/animate.css',
            'admin_style' => '/css/style.css',

            'datatables_min' => '/plugins/datatables/datatables.min.css',
            'footable_core' => '/plugins/footable/footable.core.css',

            'summernote' => '/plugins/summernote/summernote.css',
            'summernote_bs3' => '/plugins/summernote/summernote-bs3.css',
            'datepicker3' => '/plugins/datapicker/datepicker3.css',
            'chosen' => '/plugins/chosen/chosen.css',
            'select2' => '/plugins/select2/select2.css'
        ]
    ],
    'asset_sets' => [
        'js' => [
            '_all' => [
                'jquery_min',
                'bootstrap_min',
                'metisMenu',
                'slimscroll_min',
                'inspinia',
                'pace_min',
                'jquery_ui_min',
                'toastr_min',
                'admin_main'
            ],
            'index' => [
                'datatables_min',
                'datatables_init'
            ],
            'create' => [
                'bootstrap_datepicker',
                'chosen_jquery',
                'jquery_validate_min',
                'additional_methods_min',
                'select2',
                'summernote_min'
            ],
            'edit' => [
                'bootstrap_datepicker',
                'chosen_jquery',
                'jquery_validate_min',
                'additional_methods_min',
                'select2',
                'summernote_min'
            ]
        ],
        'css' => [
            '_all' => [
                'bootstrap_min',
                'font_awesome',
                'toastr_min',
                'admin_animate',
                'admin_style'
            ],
            'index' => [
                'datatables_min',
                'footable_core'
            ],
            'create' => [
                'summernote',
                'summernote_bs3',
                'datepicker3',
                'chosen',
                'select2'
            ],
            'edit' => [
                'summernote',
                'summernote_bs3',
                'datepicker3',
                'chosen',
                'select2'
            ]
        ]
    ]
]);