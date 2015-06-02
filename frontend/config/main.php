<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    
    'defaultRoute' => 'adver/index',
    //'catchAll' => ['site/index'],
    
    'basePath' => dirname(__DIR__),
    //'catchAll' => ['site/offline'],
    'controllerNamespace' => 'frontend\controllers',
	/*'modules' => [
		'v1' => [
			'class' => 'frontend\modules\v1\Module',
		],
	],*/
    'components' => [
		'request' => [
			'csrfParam' => '_frontend_csrf',
            'enableCsrfValidation' => 'true',
        ],
		'session' => [
			'name' => '_frontend_session',
            //'savePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime'. DIRECTORY_SEPARATOR .'session',
        ],
        'user' => [
            'loginUrl' => ['/user/login'],
            'identityCookie' => [
                'name' => '_frontendUser', // unique for front-end
                //'path' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime'. DIRECTORY_SEPARATOR .'session',
                'httpOnly' => true,
            ]
        ],
        'urlManager' => [
			//'enableStrictParsing' => true,
            'rules' => [
                '' => 'adver/index',
                //'<action>' => 'site/<action>',
				['pattern' => 'rss', 'route' => 'feed/rss', 'suffix' => '.xml'],
				['pattern' => 'atom', 'route' => 'feed/atom', 'suffix' => '.xml'],
				['pattern' => 'sitemap/<cat:\d+>', 'route' => 'feed/sitemap', 'suffix' => '.xml'],
				['pattern' => 'sitemap', 'route' => 'feed/sitemap', 'suffix' => '.xml'],				
                [
					'class' => 'yii\rest\UrlRule',
					'controller' => ['v1/adver'],
				],
				//['class' => 'yii\rest\UrlRule', 'controller' => ['v1/user', 'v1/post']],
				//['class' => 'yii\rest\UrlRule', 'controller' => ['v2/user', 'v2/post']],
				
				
                'user/auth/<authclient:.*?>'=>'user/auth',
                'user/reset-password/<token:.*?>'=>'user/reset-password',
            //'posts'=>'post/list',
            //'post/<id:\d+>'=>'post/read',
            //'post/<year:\d{4}>/<title>'=>'post/read',
            //'<controller:(post|comment)>/<id:\d+>/<action:(create|update|delete)>' => '<controller>/<action>',
            //'<controller:(post|comment)>/<id:\d+>' => '<controller>/read',
            //'<controller:(post|comment)>s' => '<controller>/list',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'adver/error',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'google' => [
                    //'class' => 'yii\authclient\clients\GoogleOpenId',
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                /*'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => '',
                    'consumerSecret' => '',
                ],*/
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
				'live' => [
                    'class' => 'yii\authclient\clients\Live',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ],
    ],
    'params' => $params,
];
