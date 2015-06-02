<?php

namespace backend\controllers\user;

use Yii;
use yii\base\Action;

class Delete extends Action {

    public function run($id) {
        $id = (int)$id;
        if (($user = \common\models\user\User::findOne($id)) !== null) {
			if (!Yii::$app->getAuthManager()->checkAccess($id, 'Administrator')){
				if ($user->delete()){
					Yii::$app->getAuthManager()->revokeAll($id);
					$output = [
						'error' => false,
						'message' => Yii::t('app', 'Successfully deleted!'),
					];
				}
			}
			else{
				$output = [
					'error' => true,
					'message' => Yii::t('app', Yii::t('app', "You haven't enough permission to delete this user!")),
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