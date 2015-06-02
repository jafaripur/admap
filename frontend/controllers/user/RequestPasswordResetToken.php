<?php
namespace frontend\controllers\user;

use Yii;
use frontend\models\user\PasswordResetRequestForm;
use yii\base\Action;

class RequestPasswordResetToken extends Action
{    
    public function run()
    {
        $model = new PasswordResetRequestForm();
        $success = false;
        $message = '';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $message = Yii::t('app', 'Check your email for further instructions.');
                $success = true;
            } else {
                $message = Yii::t('app', 'Sorry, we are unable to reset password for email provided.');
                $success = false;
            }
        }
        
        return Yii::$app->request->isAjax ? 
                $this->controller->renderAjax('requestPasswordResetToken', [
                    'model' => $model,
                    'success' => $success,
                    'message' => $message
                ])
                :
                $this->controller->render('requestPasswordResetToken', [
                    'model' => $model,
                    'success' => $success,
                    'message' => $message
                ]);
    }
}
