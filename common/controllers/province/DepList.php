<?php

namespace common\controllers\province;

use Yii;
use yii\base\Action;
use common\models\province\Province;
use yii\db\Query;
use yii\helpers\Json;

class DepList extends Action {

    public function run() {
        $out = [];
        $parents = Yii::$app->getRequest()->post('depdrop_parents', null);
        if($parents != null) {
            $country_id = $parents[0];
            $out = Province::find()
                ->select([
                    'id',
                    'name',
                ])
                    ->where(['country_id' => $country_id])
                    ->asArray()->all();
            
            return Json::encode(['output' => $out, 'selected' => '']);
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
}