<?php

namespace backend\controllers\city;

use Yii;
use yii\base\Action;
use backend\models\city\CitySearch;

class Index extends Action {

    public function run() {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}