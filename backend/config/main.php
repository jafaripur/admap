<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'admin/index',
    'modules' => [
        'roles' => [
            'class' => 'mdm\admin\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Roles'],
                    ],
                ]
            ],
        ]
    ],
    
        
    'components' => [
		'request' => [
			'csrfParam' => '_backend_csrf',
            'enableCsrfValidation' => 'true',
        ],
		'session' => [
			'name' => '_backend_session',
            //'savePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime'. DIRECTORY_SEPARATOR .'session',
        ],
        'urlManager' => [
            'rules' => [
                '' => 'admin/index',
            ]
        ],
        'user' => [
            'loginUrl' => ['/admin/login'],
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
                //'path' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime'. DIRECTORY_SEPARATOR .'session',
                'httpOnly' => true,
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'admin/error',
        ],
                
    ],    
    'params' => $params,
];