<?php
namespace backend\controllers;
use common\models\city\City;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * City controller
 */
class CityController extends Controller
{
    public $enableCsrfValidation = true;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['CityList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['CityAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['CityDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['CityEdit'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['city-list'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'backend\controllers\city\Index',
            ],
            'add' => [
                'class' => 'backend\controllers\city\add',
            ],
            'delete' => [
                'class' => 'backend\controllers\city\Delete',
            ],
            'update' => [
                'class' => 'backend\controllers\city\Update',
            ],
            'city-list' => [
                'class' => 'common\controllers\city\CityList',
            ],
        ];
    }
    
    public function findModel($id)
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
