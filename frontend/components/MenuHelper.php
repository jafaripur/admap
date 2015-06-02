<?php

/**
 * Class for generate Menu item for front-end
 * 
 * @author A.Jafaripur <mjafaripur@yahoo.com>
 * 
 */
namespace frontend\components;

use Yii;

class MenuHelper
{
	
	/**
	 * create array for top menu in front-end
	 *
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return array menu items
	 */
    public static function getFrontendTopMenuItem()
    {
        $menuItems = [
            ['label' => Yii::t('app', 'Home'), 'url' => ['/adver/index']],
        ];
        $menuItems[] = ['label' => Yii::t('app', 'Register Advertisement'),
            'url' => Yii::$app->user->isGuest ? ['/user/login'] : ['/adver/register'],
            'linkOptions' => [
                'class' => Yii::$app->user->isGuest ? 'fancybox fancybox.ajax' : '',
            ]];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => Yii::t('app', 'Register'), 'url' => ['/user/register'], 'linkOptions' => [
                'class' => 'fancybox fancybox.ajax'
            ]];
            $menuItems[] = [
                'label' => Yii::t('app', 'Login'),
                'url' => ['/user/login'],
                'linkOptions' => [
                    'class' => 'fancybox fancybox.ajax',
                ]
            ];
        } else {
            $menuItems[] = [
                'label' => Yii::t('app', 'Panel'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Update information'),
                        'url' => ['/user/update'],
                        'linkOptions' => [
                            'class' => 'fancybox fancybox.ajax',
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'My advertisement'),
                        'url' => ['/adver/adver-list']
                    ],
                ]
            ];
            $menuItems[] = [
                'label' => Yii::t('app', 'Logout ({username})', [
                    'username' => Yii::$app->user->identity->username,
                ]),
                'url' => ['/user/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        }
		
		$menuItems[] = [
			'label' => Yii::t('app', 'Language'),
			'items' => Yii::$app->helper->getLanguages(),
		];
		
		$menuItems[] = [
			'label' => Yii::t('app', 'Contact with us'),
			'url' => ['/main/contact'],
		];
		        
        return $menuItems;
    }
}