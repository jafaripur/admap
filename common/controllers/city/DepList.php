<?php

namespace common\controllers\city;

use Yii;
use yii\base\Action;
use common\models\city\City;
use yii\db\Query;
use yii\helpers\Json;

class DepList extends Action {

    public function run() {
        $out = [];
        $parents = Yii::$app->getRequest()->post('depdrop_parents', null);
        if($parents != null) {
            $province_id = $parents[0];
            $out = City::find()
                ->select([
                    'id',
                    'name',
                ])
                    ->where(['province_id' => $province_id])
                    ->asArray()->all();
            
            return Json::encode(['output' => $out, 'selected' => '']);
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
}