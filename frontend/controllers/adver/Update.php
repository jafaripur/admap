<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\gallery\GallerySearch;
use common\models\attachment\AttachmentSearch;

class Update extends Action {

    public function run($id) {
        $id = (int) $id;
        $adverModel = $this->controller->findModel($id);
        if (!Yii::$app->getUser()->can('AdverOwnUpdate', ['adver' => $adverModel])){
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        if ($adverModel->load(Yii::$app->request->post()) && $adverModel->validate()) {
            if ($adverModel->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Advertisement successfully saved.'));
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error on saving advertisement.'));
            }
        }
		
		$gallerySearchModel = new GallerySearch();
        $galleryDataProvider = $gallerySearchModel->search(Yii::$app->request->get(), $id);
		$galleryDataProvider->pagination->pageParam = 'gallery-page';
		$galleryDataProvider->sort->sortParam = 'gallery-sort';
		
		
		$attachmentSearchModel = new AttachmentSearch();
		$attachmentDataProvider = $attachmentSearchModel->search(Yii::$app->request->get(), $id);
		$attachmentDataProvider->pagination->pageParam = 'attachment-page';
		$attachmentDataProvider->sort->sortParam = 'attachment-sort';
        
        return $this->controller->render('@common/views/adver/manage', [
            'adverModel' => $adverModel,
			'gallery' => new \common\models\gallery\Gallery(['scenario' => 'new']),
			'galleryDataProvider' => $galleryDataProvider,
            'gallerySearchModel' => $gallerySearchModel,
			
			'attachment' => new \common\models\attachment\Attachment(['scenario' => 'new']),
			'attachmentDataProvider' => $attachmentDataProvider,
            'attachmentSearchModel' => $attachmentSearchModel,
        ]);   
    }
}