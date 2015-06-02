<?php

namespace frontend\controllers\adver;

use Yii;
use yii\base\Action;
use common\models\adver\Adver;
use common\models\gallery\Gallery;
use common\models\attachment\Attachment;
use yii\helpers\Json;
use yii\helpers\Html;

class InfoWindow extends Action {

	public function run($ids) {

		$cacheKey = __NAMESPACE__ . __CLASS__ . 'infowindow' . $ids;
		$ids = explode('-', $ids);
		$makeCacheParam = [];
		foreach ($ids as $id) {
			$makeCacheParam[':p' . $id] = $id;
		}
		$makeCacheParam[':status'] = Adver::STATUS_ACTIVE;
		$cacheWhere = implode(',', array_keys($makeCacheParam));
		$cache = Yii::$app->getCache();

		if (!$model = $cache->get($cacheKey)) {
			$model = Adver::find()
					->select([
						'id',
						'category_id',
						'country_id',
						'province_id',
						'city_id',
						'city_id',
						'user_id',
						'title',
						'address',
						'lang'
					])
					->with([
						/* 'attachment' => function($query){
						  $query->select([
						  'id',
						  'adver_id',
						  'name',
						  'title'
						  ]);
						  }, */
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
						->andWhere(['id' => $ids])
						->andWhere(['status' => Adver::STATUS_ACTIVE])
						->asArray()->all();

				$cache->set($cacheKey, $model, 2592000, new \yii\caching\DbDependency([
					'sql' => "SELECT MAX([[updated_at]]) FROM {{%adver}} WHERE [[id]] IN ({$cacheWhere})",
					'params' => $makeCacheParam
				]));
			}

			$newModel = [];
			foreach ($model as $key => $value) {
				$address = '';
				if (isset($model[$key]['country']['name']) && $model[$key]['country']['name'] != '')
					$address .= $model[$key]['country']['name'] . ', ';
				if (isset($model[$key]['province']['name']) && $model[$key]['province']['name'] != '')
					$address .= $model[$key]['province']['name'] . ', ';
				if (isset($model[$key]['city']['name']) && $model[$key]['city']['name'] != '')
					$address .= $model[$key]['city']['name'] . ', ';
				$address = rtrim($address, ', ');
				$newModel[$key]['full_address'] = Html::encode($address);
				$newModel[$key]['address'] = Html::encode($model[$key]['address']);
				$newModel[$key]['title'] = Html::encode($model[$key]['title']);
				$newModel[$key]['category'] = Html::encode($model[$key]['category']['name']);
				$newModel[$key]['url'] = Adver::generateLink($model[$key]['id'], $model[$key]['title'], $model[$key]['category']['name'], $model[$key]['country']['name'], $model[$key]['province']['name'], $model[$key]['city']['name'], $model[$key]['address'], $model[$key]['lang']);

				$newGallery = [];
				foreach ($value['gallery'] as $gallery) {
					$newGallery[] = [
						'url' => Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id'], 160, 105),
						'title' => Html::encode($gallery['title']),
						'adver_id' => $gallery['adver_id'],
					];
				}
				$newModel[$key]['gallery'] = $newGallery;
			}

			return Json::encode($newModel);
	}
}
	