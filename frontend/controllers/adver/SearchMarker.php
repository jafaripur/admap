<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use yii\helpers\Json;

class SearchMarker extends Action {

    public function run($lat_max, $lat_min, $lng_min, $lng_max) {
		$searchModel = new \frontend\models\adver\Search(Yii::$app->getRequest()->get());
		$data = $searchModel->searchMarker($lat_max, $lat_min, $lng_min, $lng_max);
		return Json::encode($data);
    }
}