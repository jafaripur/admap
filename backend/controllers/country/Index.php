<?php

namespace backend\controllers\country;

use Yii;
use yii\base\Action;
use backend\models\country\CountrySearch;

class Index extends Action {

    public function run() {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}