<?php

namespace backend\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\adver\Adver;

class Disable extends Action {

    public function run($id) {

        $id = (int) $id;
		$output = [];
        
		if (($model = Adver::findOne($id)) !== null) {
			if ($model->status == Adver::STATUS_ACTIVE){
				$model->status = Adver::STATUS_DISABLE;
			}
			else{
				$model->status = Adver::STATUS_ACTIVE;
			}
			if ($model->save()){
				$output = [
					'error' => false,
					'message' => Yii::t('app', 'Successfully status changed!'),
				];
			}
		}
		
		if (empty($output)){
			$output = [
				'error' => true,
				'message' => Yii::t('app', 'The requested page does not exist.'),
			];
		}
        
		return \yii\helpers\Json::encode($output);
		
    }
}