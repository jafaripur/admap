<?php
namespace backend\controllers;
use common\models\country\Country;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Country controller
 */
class CountryController extends Controller
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
                        'roles' => ['CountryList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['CountryAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['CountryDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['CountryEdit'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['country-list'],
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
                'class' => 'backend\controllers\country\Index',
            ],
            'add' => [
                'class' => 'backend\controllers\country\add',
            ],
            'delete' => [
                'class' => 'backend\controllers\country\Delete',
            ],
            'update' => [
                'class' => 'backend\controllers\country\Update',
            ],
            'country-list' => [
                'class' => 'common\controllers\country\CountryList',
            ],
        ];
    }
    
    public function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
