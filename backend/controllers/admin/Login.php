<?php
namespace backend\controllers\admin;

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
            return $this->controller->render('login', [
                'model' => $model,
            ]);
        }
    }
}
