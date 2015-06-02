<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use common\models\attachment\Attachment;
use common\models\adver\Adver;
use yii\helpers\FileHelper;

class AttachmentAdd extends Action {

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
        
        if (empty($output) && !Attachment::checkLimitation($id)){
			$output = [
				'error' => true,
				'message' => '<div class="alert alert-danger">' . Yii::t('app', 'Attachment limitation per advertisement reached!') . '</div>'
			];
        }
        if (empty($output)){
			$model = new Attachment(['scenario' => 'new']);
			if ($model->load(Yii::$app->request->post())) {
				$model->attachment = UploadedFile::getInstance($model, 'attachment');
				if ($model->attachment){
					$model->name = Yii::$app->helper->safeFile($model->attachment->baseName) . '-' . Yii::$app->getSecurity()->generateRandomString(6) .'-'.time() .'.' . $model->attachment->extension;
					$path = $model->getAttachmentPath($id) . DIRECTORY_SEPARATOR . $model->name;
					$path = FileHelper::normalizePath($path);
					$model->adver_id = $id;
					if ($model->validate() && $model->attachment->saveAs($path , false)){
						if ($model->save()){
							$output = [
								'error' => false,
								'message' => '<div class="alert alert-success">' . Yii::t('app', 'Attachment saved!') . '</div>',
							];
						}
						else{
							$output = [
								'error' => true,
								'message' => '<div class="alert alert-danger">' . Yii::t('app', 'Error on saving attachment.') . '</div>'
							];
						}
					}
				}
			}
			//print_r($model);
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