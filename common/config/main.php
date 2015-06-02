<?php

return [
    'name' => 'admap.ir',
    'version' => '1.0.9',
    'charset' => 'UTF-8',
    'timeZone' => 'Asia/Tehran',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'fa-IR',
    'bootstrap' => ['log'],
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\user\User',
            'enableAutoLogin' => true,
        ],
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
			'keyPrefix' => 'admap',
			'directoryLevel' => 2,
			'cachePath' => '@backend/runtime/cache',
        ],
        'session' => [
			'class' => 'yii\web\Session',
            //'class' => 'yii\web\CacheSession',
            //'cache' => 'cache',
            //'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
			
			'enableDefaultLanguageUrlCode' => false,
            'enableLanguagePersistence' => true,
			'languages' => [
                'en',
                'fa' => 'fa-IR',
                //'fa-IR',
            ],
			
            //'class' => 'yii\web\urlManager',
            //'baseUrl' => '/Admap/frontend/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
				//'adver/view/<id:\d+>/<title:\w+>'=>'adver/view',
				'adver/view/<id:\d+>/<title:.*?>'=>'adver/view',
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
            ]
        ],
        'i18n' => [
            'translations' => [
                'yii' =>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/yiisoft/yii2/messages',
                    //'sourceLanguage' => 'fa-IR',
                    'fileMap' => [
                        'yii' => 'yii.php',
                        //'app/error' => 'error.php',
                    ],
                ],
                 
                'app*' =>[
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@common/messages',
                    //'sourceLanguage' => 'fa-IR',
                    //'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager', //'yii\rbac\PhpManager', // or use 'yii\rbac\DbManager'
            'defaultRoles' => ['Default'],
			'cache' => 'cache',
        ],
        
        'assetManager' => [
            'linkAssets' => false,
            //'bundles' => require(__DIR__ . '/' . (YII_DEBUG ? 'assets-dev.php' : 'assets-prod.php'))
			'bundles' => require(__DIR__ . DIRECTORY_SEPARATOR . 'assets-dev.php'),
			'appendTimestamp' => true,
        ],
        'dateTimeAction' => [
            'class' => 'common\components\DateTimeAction',
        ],
        'helper' => [
            'class' => 'common\components\Helper',
        ],
        'imageHelper' => [
            'class' => 'common\components\ImageHelper',
        ]
    ],   
];
