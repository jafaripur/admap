<?php

namespace common\controllers\categories;

use Yii;
use yii\base\Action;
use common\models\categories\Categories;
use yii\db\Query;
use yii\helpers\Json;

class CategoriesList extends Action {

    public function run($search = null, $id = null) {

        $out = ['more' => false];
        if(!is_null($search)) {
            $query = new Query();
            $query->select('[[id]], [[name]] AS [[text]]')
                    ->from('{{%categories}}')
                    ->filterWhere(['like', '[[name]]', $search])
                    ->andWhere(['[[status]]' => Categories::STATUS_ACTIVE])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif($id > 0) {
            $cat = Categories::findOne([
                'id' => $id,
                'status' => Categories::STATUS_ACTIVE
            ]);
            $out['results'] = ['id' => $id, 'text' => $cat->name];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => Yii::t('app', 'No matching records found')];
        }
        return Json::encode($out);
    }
}