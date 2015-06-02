<?php

namespace common\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\attachment\Attachment;

class DownloadAttachment extends Action {

    public function run($id) {
		$id = (int)$id;
		if (!$model = Attachment::findOne($id)) {
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested file does not exist.'));
		}
		$path = $model->getAttachment();
		if (!$path){
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested file does not exist.'));
		}
		Yii::$app->getResponse()->sendFile($path);
    }
}