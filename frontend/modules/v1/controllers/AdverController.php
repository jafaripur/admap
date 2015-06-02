<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\adver\Adver;


use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * Rest Adver controller
 */
class AdverController extends Controller
{
		
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],*/
			/*'index' => [
                'class' => 'frontend\modules\v1\controllers\adver\Index',
            ],*/
        ];
    }
	
	public function actionIndex(){
		return new ActiveDataProvider([
            'query' => Adver::find(),
        ]);
	}
}
