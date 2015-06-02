<?php

namespace frontend\controllers\feed;

use Yii;
use yii\base\Action;
use frontend\controllers\FeedController;

class Atom extends Action {

    public function run() {
		
		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/xml');
		
		return $this->controller->getFeed(FeedController::ATOM);
    }
}