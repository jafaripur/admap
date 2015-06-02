<?php

namespace common\controllers\province;

use Yii;
use yii\base\Action;
use common\models\province\Province;
use yii\db\Query;
use yii\helpers\Json;

class ProvinceList extends Action {

    public function run($search = null, $id = null) {
        $out = ['more' => false];
        if(!is_null($search)) {
            $query = new Query();
            $query->select('[[id]], [[name]] AS [[text]]')
                    ->from('{{%province}}')
                    ->filterWhere(['like', '[[name]]', $search])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Province::findOne($id)->name];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => Yii::t('app', 'No matching records found')];
        }
        return Json::encode($out);
    }
}