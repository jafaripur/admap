<?php
namespace frontend\controllers\user;

use Yii;
use frontend\models\user\PasswordResetRequestForm;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AjaxRequestResetPasswordValidation extends Action
{    
    public function run()
    {
        $model = new PasswordResetRequestForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}
