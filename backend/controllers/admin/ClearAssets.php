<?php
namespace backend\controllers\admin;

use Yii;
use yii\base\Action;

class ClearAssets extends Action
{    
    public function run()
    {
        Yii::$app->helper->removeDirectories(Yii::getAlias('@backend/web/assets'));
		Yii::$app->helper->removeDirectories(Yii::getAlias('@frontend/web/assets'));
    }
}
