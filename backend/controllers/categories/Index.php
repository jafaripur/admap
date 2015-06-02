<?php

namespace backend\controllers\categories;

use Yii;
use yii\base\Action;
use backend\models\categories\CategoriesSearch;

class Index extends Action {

    public function run() {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
        ]);
    }
}