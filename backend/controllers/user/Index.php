<?php

namespace backend\controllers\user;

use Yii;
use yii\base\Action;
use backend\models\user\UserSearch;

class Index extends Action {

    public function run() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }
}