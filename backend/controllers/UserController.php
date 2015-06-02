<?php
namespace backend\controllers;
use common\models\user\User;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * User controller
 */
class UserController extends Controller
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
                        'roles' => ['UserList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['UserDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['UserEdit'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['UserDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['user-list'],
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
                'class' => 'backend\controllers\user\Index',
            ],
            'delete' => [
                'class' => 'backend\controllers\user\Delete',
            ],
            'update' => [
                'class' => 'backend\controllers\user\Update',
            ],
            'user-list' => [
                'class' => 'common\controllers\user\UserList',
            ],
			'disable' => [
                'class' => 'backend\controllers\user\Disable',
            ],
        ];
    }
    
    public function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
