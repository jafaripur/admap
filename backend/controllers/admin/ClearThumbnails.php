<?php
namespace backend\controllers\admin;

use Yii;
use yii\base\Action;

class ClearThumbnails extends Action
{    
    public function run()
    {
        Yii::$app->helper->removeDirectories(Yii::getAlias('@backend/web/images'));
		Yii::$app->helper->removeDirectories(Yii::getAlias('@frontend/web/images'));
    }
}
