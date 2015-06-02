<?php
namespace backend\controllers;
use common\models\categories\Categories;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


/**
 * Categories controller
 */
class CategoriesController extends Controller
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
				'except' => ['disable'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['CategoryAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['CategoryList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['CategoryDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['CategoryEdit'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['CategoryDisable'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['categories-list'],
                        'roles' => ['@'],
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
                'class' => 'backend\controllers\categories\Index',
            ],
            'add' => [
                'class' => 'backend\controllers\categories\Add',
            ],
            'delete' => [
                'class' => 'backend\controllers\categories\Delete',
            ],
            'update' => [
                'class' => 'backend\controllers\categories\Update',
            ],
			'categories-list' => [
                'class' => 'common\controllers\categories\CategoriesList',
            ],
			'disable' => [
                'class' => 'backend\controllers\categories\Disable',
            ],
        ];
    }
    
    public function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
