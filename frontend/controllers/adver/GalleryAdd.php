<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use common\models\gallery\Gallery;
use common\models\adver\Adver;
use yii\helpers\FileHelper;

class GalleryAdd extends Action {

    public function run($id) {
        $id = (int)$id;
        $userId = Adver::getUserIdFromAdver($id);
		$output = [];
        if ($userId != Yii::$app->getUser()->id){
			$output = [
				'error' => true,
				'message' => '<div class="alert alert-danger">' . Yii::t('app', 'The requested page does not exist.') . '</div>'
			];
        }
        
        if (empty($output) && !Gallery::checkLimitation($id)){
			$output = [
				'error' => true,
				'message' => '<div class="alert alert-danger">' . Yii::t('app', 'Image limitation per advertisement reached!') . '</div>'
			];
        }
        if (empty($output)){
			$model = new Gallery(['scenario' => 'new']);
			if ($model->load(Yii::$app->request->post())) {
				$model->image = UploadedFile::getInstance($model, 'image');
				if ($model->image){
					$model->name = Yii::$app->helper->safeFile($model->image->baseName) . '-' . Yii::$app->getSecurity()->generateRandomString(6) .'-'.time() .'.' . $model->image->extension;
					$path = $model->getImagePath($id) . DIRECTORY_SEPARATOR . $model->name;
					$path = FileHelper::normalizePath($path);
					$model->adver_id = $id;
					if ($model->validate() && $model->image->saveAs($path , false)){
						if ($model->save()){
							$output = [
								'error' => false,
								'message' => '<div class="alert alert-success">' . Yii::t('app', 'Image saved!') . '</div>',
							];
						}
						else{
							$output = [
								'error' => true,
								'message' => '<div class="alert alert-danger">' . Yii::t('app', 'Error on saving image.') . '</div>'
							];
						}
					}
				}
			}
			
			if (empty($output)){
				$output = [
					'error' => true,
					'message' => \yii\helpers\Html::errorSummary($model, ["class" => "alert alert-danger"]),
				];
			}
			
		}
        
        return \yii\helpers\Json::encode ($output);
    }
}