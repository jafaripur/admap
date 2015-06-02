<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\adver\Adver;
use yii\helpers\HtmlPurifier;

class View extends Action {

	public function run($id, $title) {
		$id = (int)$id;
		$cacheKey = __NAMESPACE__ . __CLASS__ . 'adver.view' . $id;
		$cache = Yii::$app->getCache();
		
		if (!$model = $cache->get($cacheKey)) {
			$model = Adver::find()
				->with([
					'attachment' => function($query){
						$query->select([
							'id',
							'adver_id',
							'name',
							'title'
						]);
					},
					'gallery' => function($query) {
						$query->select([
							'id',
							'adver_id',
							'name',
							'title'
						]);
					},
					'category' => function($query) {
						$query->select([
							'id',
							'name',
						]);
					},
					'country' => function($query) {
						$query->select([
							'id',
							'name',
						]);
					},
					'province' => function($query) {
						$query->select([
							'id',
							'name',
						]);
					},
					'city' => function($query) {
						$query->select([
							'id',
							'name',
						]);
					},
				])
					->where([
						'id' => $id,
						'status' => Adver::STATUS_ACTIVE,
						'lang' => ['*', Yii::$app->language],
					])
					->asArray()->one();
				if (!$model){
					throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
				}
			$model['description'] = HtmlPurifier::process($model['description']);
			
			$cache->set($cacheKey, $model, 2592000, new \yii\caching\DbDependency([
				'sql' => "SELECT [[updated_at]] FROM {{%adver}} WHERE [[id]] = :id AND [[status]] = :status",
				'params' => [
					':id' => $id,
					':status' => Adver::STATUS_ACTIVE
				]
			]));
		}
						
		return $this->controller->render('view', [
			'model' => $model,
		]);
		
	}
}
	