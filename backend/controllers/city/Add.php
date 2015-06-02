<?php

namespace backend\controllers\city;

use Yii;
use yii\base\Action;
use common\models\city\City;

class Add extends Action {

    public function run() {
        $model = new City();
                        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'City successfully saved.'));
                $model = new City();
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving city.'));
            }
        }
        
        return $this->controller->render('manage', [
            'model' => $model,
        ]);   
    }
}