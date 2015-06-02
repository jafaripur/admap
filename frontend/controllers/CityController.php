<?php
namespace frontend\controllers;
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'dep-list' => [
                'class' => 'common\controllers\city\DepList',
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
