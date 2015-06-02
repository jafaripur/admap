<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\user\User;

/**
 * User controller
 */
class UserController extends Controller
{
    public $enableCsrfValidation = true;
    public $defaultAction = 'index';
    
    public function beforeAction($action) {
        /*
        switch($action->id)
        {
            case 'ajaxSignupValidation':
            case 'signup':
            case 'ajaxResetPasswordValidation':
            case 'ajaxSignupValidation':
            case 'resetPassword':
                $this->enableCsrfValidation = true;
                break;
        }
         */
        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'logout',
                    'register',
                    'ajaxRegisterValidation',
                    'ajaxRequestResetPasswordValidation',
                ],
                'rules' => [
                    [
                        'actions' => ['register', 'ajaxRegisterValidation'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'ajaxRequestResetPasswordValidation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'ajax-request-resetPassword-validation' => ['post'],
                    'ajax-register-validation' => ['post'],
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
            'login' => [
                'class' => 'frontend\controllers\user\Login',
            ],
            'register' => [
                'class' => 'frontend\controllers\user\Register',
            ],
            'update' => [
                'class' => 'frontend\controllers\user\Update',
            ],
            'ajax-request-reset-password-validation' => [
                'class' => 'frontend\controllers\user\AjaxRequestResetPasswordValidation',
            ],
            'ajax-register-validation' => [
                'class' => 'frontend\controllers\user\AjaxRegisterValidation',
            ],
            'request-password-reset-token' => [
                'class' => 'frontend\controllers\user\RequestPasswordResetToken',
            ],
            'reset-password' => [
                'class' => 'frontend\controllers\user\ResetPassword',
            ],
            'user-list' => [
                'class' => 'common\controllers\user\UserList',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 2,
                'maxLength' => 4,
                //'fixedVerifyCode' => YII_DEBUG ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
				'successCallback' => ['frontend\components\AuthCallback', 'successCallback'],
				
                //'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }
	
	

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
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
