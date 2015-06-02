<?php

namespace backend\controllers\user;

use Yii;
use yii\base\Action;
use backend\models\user\UpdateInformationForm;
use backend\models\user\UpdatePasswordForm;

class Update extends Action {

    public function run($id) {
        
        $user = $this->controller->findModel($id);
                
        $updatePassword = new UpdatePasswordForm();
        $updateInformation = new UpdateInformationForm();
        $updateInformation->setUserData($user);
        
        if ($updatePassword->load(Yii::$app->request->post())) {
            $updatePassword->updatePassword($user);
        }
        
        if ($updateInformation->load(Yii::$app->request->post())) {
            $updateInformation->updateInformation($user);
        }

        return Yii::$app->request->isAjax ? 
                $this->controller->renderAjax('update', [
                    'updatePassword' => $updatePassword,
                    'updateInformation' => $updateInformation,
                ])
                :
                $this->controller->render('update', [
                    'updatePassword' => $updatePassword,
                    'updateInformation' => $updateInformation,
                ]);   
    }
}