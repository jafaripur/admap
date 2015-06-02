<?php
namespace backend\controllers;
use common\models\province\Province;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Province controller
 */
class ProvinceController extends Controller
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
                        'roles' => ['ProvinceList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['ProvinceAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['ProvinceDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['ProvinceEdit'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['province-list'],
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
                'class' => 'backend\controllers\province\Index',
            ],
            'add' => [
                'class' => 'backend\controllers\province\add',
            ],
            'delete' => [
                'class' => 'backend\controllers\province\Delete',
            ],
            'update' => [
                'class' => 'backend\controllers\province\Update',
            ],
            'province-list' => [
                'class' => 'common\controllers\province\ProvinceList',
            ],
        ];
    }
    
    public function findModel($id)
    {
        if (($model = Province::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
