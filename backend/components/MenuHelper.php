<?php

/**
 * Class for generate Menu item for backend-end
 * 
 * @author A.Jafaripur <mjafaripur@yahoo.com>
 * 
 */

namespace backend\components;

use Yii;

class MenuHelper
{
	
	/**
	 * create array for top menu in back-end
	 *
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return array menu items
	 */
    public static function getAdminTopMenuItem()
    {
        $menuItems = [
            ['label' => Yii::t('app', 'Home'), 'url' => ['/admin/index']],
        ];
		if (Yii::$app->getUser()->can('Admin')){
			$menuItems[] = [
				'label' => Yii::t('app', 'Role Manager'),
				'items' => array_values(Yii::$app->getModule('roles')->getMenus()),
				'visible' => Yii::$app->getUser()->can('Roles'),
			];

			$menuItems[] = [
				'label' => Yii::t('app', 'Geographic'),
				'items' => [
					[
						'label' => Yii::t('app', 'Countries'),
						'url' => ['/country/index'],
						'visible' => Yii::$app->getUser()->can('CountryList'),
					],
					[
						'label' => Yii::t('app', 'Provinces'),
						'url' => ['/province/index'],
						'visible' => Yii::$app->getUser()->can('ProvinceList'),
					],
					[
						'label' => Yii::t('app', 'Cities'),
						'url' => ['/city/index'],
						'visible' => Yii::$app->getUser()->can('CityList'),
					],
				],
			];

			$menuItems[] = [
				'label' => Yii::t('app', 'Manager'),
				'items' => [
					[
						'label' => Yii::t('app', 'Users Manager'),
						'url' => ['/user/index'],
						'visible' => Yii::$app->getUser()->can('UserList'),
					],
					[
						'label' => Yii::t('app', 'Categories'),
						'url' => ['/categories/index'],
						'visible' => Yii::$app->getUser()->can('CategoryList'),
					],
					[
						'label' => Yii::t('app', 'Advertisement'),
						'url' => ['/adver/adver-list'],
						'visible' => Yii::$app->getUser()->can('AdverList'),
					],
				]
			];
		}
        if (Yii::$app->getUser()->isGuest) {
            $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/admin/login']];
        } else {
            $menuItems[] = [
                'label' => Yii::t('app', 'Logout ({username})', [
                    'username' => Yii::$app->user->identity->username,
                ]),
                'url' => ['/admin/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ];
        }
		
		$menuItems[] = [
			'label' => Yii::t('app', 'Language'),
			'items' => Yii::$app->helper->getLanguages(),
		];
        
        return $menuItems;
    }
}