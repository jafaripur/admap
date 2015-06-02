<?php
namespace backend\controllers\admin;

use Yii;
use yii\base\Action;

class ClearCache extends Action
{    
    public function run()
    {
        Yii::$app->getCache()->flush();
    }
}
