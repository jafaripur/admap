<?php

namespace backend\controllers\country;

use Yii;
use yii\base\Action;

class Delete extends Action {

    public function run($id) {
        
		$id = (int) $id;
		$output = [];
        
		if (($model = \common\models\country\Country::findOne($id)) !== null) {
			if ($model->delete()){
				$output = [
					'error' => false,
					'message' => Yii::t('app', 'Successfully deleted!'),
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