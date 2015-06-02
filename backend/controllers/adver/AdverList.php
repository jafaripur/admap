<?php
namespace backend\controllers\adver;

use Yii;
use yii\base\Action;
use backend\models\adver\AdverListSearch;

class AdverList extends Action
{    
    public function run()
    {
        $searchModel = new AdverListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('adverList', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
}
