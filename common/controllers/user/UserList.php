<?php

namespace common\controllers\user;

use Yii;
use yii\base\Action;
use common\models\user\User;
use yii\db\Query;
use yii\helpers\Json;

class UserList extends Action {

    public function run($search = null, $id = null) {
        $out = ['more' => false];
        if(!is_null($search)) {
            $query = new Query();
            $query->select('[[id]], [[username]] AS [[text]]')
					->from('{{%user}}')
					->filterWhere(['like', '[[username]]', $search])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)->username];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => Yii::t('app', 'No matching records found')];
        }
        return Json::encode($out);
    }
}