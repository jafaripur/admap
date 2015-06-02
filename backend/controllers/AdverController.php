<?php
namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\adver\Adver;

/**
 * Adver controller
 */
class AdverController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['index'],
                'rules' => [
					[
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['AdverUpdate'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['AdverDelete'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['adver-list'],
                        'roles' => ['AdverList'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['AdverDisable'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['attachment-delete'],
                        'roles' => ['AttachmentDelete'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['gallery-delete'],
                        'roles' => ['GalleryDelete'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['download-attachment'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
					'attachment-delete' => ['post'],
					'gallery-delete' => ['post'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'update' => [
                'class' => 'backend\controllers\adver\Update',
            ],
            'delete' => [
                'class' => 'backend\controllers\adver\Delete',
            ],
			'disable' => [
                'class' => 'backend\controllers\adver\Disable',
            ],
            'adver-list' => [
                'class' => 'backend\controllers\adver\AdverList',
            ],
            'gallery-delete' => [
                'class' => 'backend\controllers\adver\GalleryDelete',
            ],
			'attachment-delete' => [
                'class' => 'backend\controllers\adver\AttachmentDelete',
            ],
			'download-attachment' => [
                'class' => 'common\controllers\adver\DownloadAttachment',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function findModel($id)
    {
        if (($model = Adver::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
