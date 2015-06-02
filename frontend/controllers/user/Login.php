<?php
namespace frontend\controllers\user;

use Yii;
use common\models\user\LoginForm;
use yii\base\Action;

class Login extends Action
{    
    public function run()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->controller->goBack();
        } else {
            return Yii::$app->request->isAjax ? 
                $this->controller->renderAjax('login', [
                    'model' => $model,
                ])
                :
                $this->controller->render('login', [
                    'model' => $model,
                ]);
        }
    }
}
