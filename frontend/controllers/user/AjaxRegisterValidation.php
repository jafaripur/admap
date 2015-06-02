<?php
namespace frontend\controllers\user;

use Yii;
use frontend\models\user\RegisterForm;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AjaxRegisterValidation extends Action
{
    public function run()
    {
        $model = new RegisterForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}
