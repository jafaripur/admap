<?php

namespace backend\controllers\categories;

use Yii;
use yii\base\Action;

class Update extends Action {

    public function run($id) {
        
        $id = (int) $id;
        $model = $this->controller->findModel($id);
                
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Category updated.'));
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving category.'));
            }
        }
        
        return Yii::$app->request->isAjax ? 
                $this->controller->renderAjax('manage', [
                    'model' => $model,
                ])
                :
                $this->controller->render('manage', [
                    'model' => $model,
                ]);
    }
}