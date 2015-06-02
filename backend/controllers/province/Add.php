<?php

namespace backend\controllers\province;

use Yii;
use yii\base\Action;
use common\models\province\Province;

class Add extends Action {

    public function run() {
        $model = new Province();
                        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Province successfully saved.'));
                $model = new Province();
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving province.'));
            }
        }
        
        return $this->controller->render('manage', [
                    'model' => $model,
                ]);   
    }
}