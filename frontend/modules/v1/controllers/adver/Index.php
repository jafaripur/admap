<?php

namespace frontend\modules\v1\controllers\adver;

use yii\rest\Action;
use yii\data\ActiveDataProvider;
use common\models\adver\Adver;

class Index extends Action {

    public function run() {

        return new ActiveDataProvider([
            'query' => Adver::find(),
        ]);
		
    }
}