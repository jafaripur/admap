<?php

namespace frontend\controllers\feed;

use Yii;
use yii\base\Action;
use common\models\categories\Categories;
use yii\helpers\Url;
use common\models\adver\Adver;
use common\models\gallery\Gallery;

class Sitemap extends Action {

    public function run($cat = 0) {
		
		$cat = (int) $cat;
		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/xml');
		
		$content = '<?xml version="1.0" encoding="UTF-8"?>';
		
		if ($cat <= 0){
			$categories = Categories::find()
				->where(['status' => Categories::STATUS_ACTIVE])
				->select([
					'id',
				])
				->asArray()
				->all();
			$content .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			foreach($categories as $category){
				$content .= '<sitemap>';
				$content .= '<loc>'. Url::to(['/feed/sitemap', 'cat' => $category['id']], true).'</loc>';
				$content .= '</sitemap>';
			}
            $content .= '</sitemapindex>';
		}
		else{
			$advers = $this->controller->getModel(null, $cat);
			$content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			foreach($advers as $adver){
				$url = urldecode(Adver::generateLink($adver['id'], $adver['title'], $adver['category']['name'],
						$adver['country']['name'], $adver['province']['name'], 
						$adver['city']['name'], $adver['address'], $adver['lang'], true));
				$content .= '<url>';
                $content .= "<loc>{$url}</loc>";
				$content .= "<changefreq>daily</changefreq>";
                $content .= '<priority>0.5</priority>';
                $content .= '<lastmod>'.date(DATE_W3C, $adver['updated_at']).'</lastmod>';
				foreach($adver['gallery'] as $gallery){
					$content .= '<image:image><image:loc>'.Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id'], 0, 0, 70, true).'</image:loc></image:image>';
				}
                $content .= '</url>';
			}
			$content .= '</urlset>';
		}
		
		return $content;
    }
}