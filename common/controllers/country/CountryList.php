<?php

namespace common\controllers\country;

use Yii;
use yii\base\Action;
use common\models\country\Country;
use yii\db\Query;
use yii\helpers\Json;

class CountryList extends Action {

    public function run($search = null, $id = null) {
        $out = ['more' => false];
        if(!is_null($search)) {
            $query = new Query();
            $query->select('[[id]], [[name]] AS [[text]]')
                    ->from('{{%country}}')
                    ->filterWhere(['like', '[[name]]', $search])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Country::findOne($id)->name];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => Yii::t('app', 'No matching records found')];
        }
        return Json::encode($out);
    }
}