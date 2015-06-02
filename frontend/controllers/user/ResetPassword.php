<?php
namespace frontend\controllers\user;

use Yii;
use frontend\models\user\ResetPasswordForm;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\helpers\Url;

class ResetPassword extends Action
{    
    public function run($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
		
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return Yii::$app->getResponse()->redirect(Url::to(['user/login']));
        }

        return $this->controller->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
