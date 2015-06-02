<?php

namespace backend\controllers\user;

use Yii;
use yii\base\Action;
use common\models\user\User;

class Disable extends Action {

    public function run($id) {

        $id = (int) $id;
		$output = [];
        
		if (($model = User::findOne($id)) !== null) {
			if ($model->status == User::STATUS_ACTIVE){
				$model->status = User::STATUS_DISABLE;
			}
			else{
				$model->status = User::STATUS_ACTIVE;
			}
			if (Yii::$app->getAuthManager()->checkAccess($id, 'Administrator')){
				$output = [
						'error' => true,
						'message' => Yii::t('app', "You haven't enough permission to disable this user!"),
					];
			}
			else{
				if ($model->save()){
					$output = [
						'error' => false,
						'message' => Yii::t('app', 'Successfully status changed!'),
					];
				}
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