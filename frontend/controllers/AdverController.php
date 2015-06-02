<?php
namespace frontend\controllers;

use Yii;
//use yii\base\InvalidParamException;
//use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\adver\Adver;
//use common\models\gallery\Gallery;

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
                'except' => ['index', 'error'],
                'rules' => [
                    [
                        'allow' => true,
                        //'actions' => ['register', 'adver-list', 'delete', 'update', 'gallery'],
                        'roles' => ['@'],
                    ],
					[
                        'allow' => true,
                        'actions' => ['download-attachment', 'index', 'search-cluster', 'search-marker', 'info-window', 'view', 'qr-code', 'error'],
                        'roles' => ['?'],
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
			/*[
				'class' => 'yii\filters\HttpCache',
				'only' => ['index'],
				'lastModified' => function ($action, $params) {
					$q = new \yii\db\Query();
					return $q->from('adver')->max('updated_at');
				},
			],*/
			[
				'class' => 'yii\filters\HttpCache',
				'only' => ['view'],
				'etagSeed' => function ($action, $params) {
					$model = $this->findModel((int)Yii::$app->request->get('id'));
					return serialize([
						$model->id,
						$model->updated_at,
					]);
				},
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
			'index' => [
                'class' => 'frontend\controllers\adver\Index',
            ],
            'register' => [
                'class' => 'frontend\controllers\adver\Register',
            ],
            'update' => [
                'class' => 'frontend\controllers\adver\Update',
            ],
            'delete' => [
                'class' => 'frontend\controllers\adver\Delete',
            ],
            'adver-list' => [
                'class' => 'frontend\controllers\adver\AdverList',
            ],
            'gallery-add' => [
                'class' => 'frontend\controllers\adver\GalleryAdd',
            ],
            'gallery-delete' => [
                'class' => 'frontend\controllers\adver\GalleryDelete',
            ],
			'attachment-add' => [
                'class' => 'frontend\controllers\adver\AttachmentAdd',
            ],
			'attachment-delete' => [
                'class' => 'frontend\controllers\adver\AttachmentDelete',
            ],
			'download-attachment' => [
                'class' => 'common\controllers\adver\DownloadAttachment',
            ],
			'search-cluster' => [
                'class' => 'frontend\controllers\adver\SearchCluster',
            ],
			'search-marker' => [
                'class' => 'frontend\controllers\adver\SearchMarker',
            ],
			'info-window' => [
                'class' => 'frontend\controllers\adver\InfoWindow',
            ],
			'view' => [
                'class' => 'frontend\controllers\adver\View',
            ],
        ];
    }

	/*
    public function actionIndex()
    {
        return $this->render('index');
    }*/
    
    public function findModel($id)
    {
        if (($model = Adver::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
