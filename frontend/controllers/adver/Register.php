<?php
namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\adver\Adver;

class Register extends Action
{    
    public function run()
    {
        $model = new Adver();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Advertisement successfully saved.'));
                return $this->controller->redirect(['/adver/adver-list']);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving advertisement.'));
            }
        }

        return $this->controller->render('@common/views/adver/manage', [
            'adverModel' => $model,
        ]);
    }
}
