<?php
namespace frontend\controllers\user;

use Yii;
use yii\base\Action;
use frontend\models\user\RegisterForm;

class Register extends Action
{    
    public function run()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                if ($user->login(false)) {
                    return $this->controller->goHome();
                }
            }
        }

        return Yii::$app->request->isAjax ? 
                $this->controller->renderAjax('register', [
                    'model' => $model,
                ])
                :
                $this->controller->render('register', [
                    'model' => $model,
                ]);
    }
}
