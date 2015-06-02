<?php

namespace backend\controllers\province;

use Yii;
use yii\base\Action;
use backend\models\province\ProvinceSearch;

class Index extends Action {

    public function run() {
        $searchModel = new ProvinceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}