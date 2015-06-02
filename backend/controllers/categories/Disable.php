<?php

namespace backend\controllers\categories;

use Yii;
use yii\base\Action;
use common\models\categories\Categories;

class Disable extends Action {

    public function run($id) {

        $id = (int) $id;
		$output = [];
        
		if (($model = Categories::findOne($id)) !== null) {
			if ($model->status == Categories::STATUS_ACTIVE){
				$model->status = Categories::STATUS_DISABLE;
			}
			else{
				$model->status = Categories::STATUS_ACTIVE;
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