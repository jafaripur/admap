<?php

namespace backend\controllers\country;

use Yii;
use yii\base\Action;
use \common\models\country\Country;

class Add extends Action {

    public function run() {
        $model = new Country();
                        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Country successfully saved.'));
                $model = new Country();
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving country.'));
            }
        }
        
        return $this->controller->render('manage', [
                    'model' => $model,
                ]);   
    }
}