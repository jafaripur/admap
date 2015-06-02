<?php

namespace backend\controllers\categories;

use Yii;
use yii\base\Action;
use common\models\categories\Categories;

class Add extends Action {

    public function run() {
        $model = new Categories();
        if ($model->load(Yii::$app->request->post())) {
			if ($model->save()){
				Yii::$app->session->setFlash('success', Yii::t('app', 'New category successfully saved.'));
				$model = new Categories();
			}
			else{
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