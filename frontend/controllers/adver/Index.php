<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use frontend\models\adver\Search;

class Index extends Action {
	
    public function run() {
		
        $searchModel = new Search(Yii::$app->getRequest()->get());
		$dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
		
		if (!$searchModel->country_id){
			$searchModel->country_id = 1;
		}
		
        return $this->controller->render('index', [
            'searchModel' => $searchModel,
			'dataProvider' => $dataProvider
        ]);
		
    }
}