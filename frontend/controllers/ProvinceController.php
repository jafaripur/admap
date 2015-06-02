<?php
namespace frontend\controllers;
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'dep-list' => [
                'class' => 'common\controllers\province\DepList',
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
