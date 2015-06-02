<?php

return [
    'yii\web\JqueryAsset' => [
        'sourcePath' => null,
        'js' => [
            //'jquery.min.js'
            '//code.jquery.com/jquery-2.1.1.min.js'
        ],
        'jsOptions' => [
            'position' => \yii\web\View::POS_HEAD
        ]
    ],
    'yii\bootstrap\BootstrapAsset' => [
        //'sourcePath' => null,
        'js' => [
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js',
        ],
        'css' => [
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css',
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css',
        ],
        'jsOptions' => [
            'position' => \yii\web\View::POS_HEAD
        ]
    ],
];
