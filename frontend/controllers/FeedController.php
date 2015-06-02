<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\adver\Adver;
use yii\helpers\HtmlPurifier;
use yii\helpers\Html;

/**
 * Feed controller
 */
class FeedController extends Controller
{
	public $enableCsrfValidation = false;
	
	const RSS = 0;
	const ATOM = 1;
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'allow' => true,
                        'actions' => ['atom', 'rss', 'sitemap'],
                        'roles' => ['?'],
                    ],
                ],
            ],*/
			/*[
				'class' => 'yii\filters\PageCache',
                'only' => ['rss', 'atom', 'sitemap'],
				'duration' => 86400,
                'dependency' => [
					'class' => 'yii\caching\DbDependency',
					'sql' => 'SELECT COUNT(*) FROM {{%adver}}',
				],
				'variations' => [
					Yii::$app->language,
				]
            ]*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
			'atom' => [
                'class' => 'frontend\controllers\feed\Atom',
            ],
			'rss' => [
                'class' => 'frontend\controllers\feed\Rss',
            ],
			'sitemap' => [
                'class' => 'frontend\controllers\feed\Sitemap',
            ],
        ];
    }
    
	public function getFeed($mode){
		
		$feedMethod = 'rss';
		if ($mode == self::RSS)
			$feedMethod = 'rss';
		elseif ($mode == self::ATOM)
			$feedMethod = 'atom';
				
		$feed = new \Zend\Feed\Writer\Feed;
		$feed->setTitle(Yii::t('app', 'Introduce business'));
		$feed->setDescription(Yii::t('app', 'Introduce business'));
		$feed->setLink(Yii::$app->getRequest()->getHostInfo());
		$feed->setFeedLink(Yii::$app->getRequest()->getAbsoluteUrl(), $feedMethod);
		$feed->setGenerator('Admap', Yii::$app->version, Yii::$app->getRequest()->getHostInfo());
		$feed->addAuthor([
			'name'  => 'Jafaripur',
			'email' => 'mjafaripur@yahoo.com',
			'uri'   => 'http://www.jafaripur.ir',
		]);
		$feed->setDateModified(time());
		//$feed->addHub('http://pubsubhubbub.appspot.com/');
		
		foreach ($this->getModel(50) as $adver){
			$entry = $feed->createEntry();
			$entry->setId($adver['id']);
			$entry->setTitle(Html::encode($adver['title']));
			$entry->addCategory([
				'term' => Html::encode($adver['category']['name']),
				'label' => Html::encode($adver['category']['name']),
			]);
			$entry->setLink(urldecode(Adver::generateLink($adver['id'], $adver['title'], $adver['category']['name'],
				$adver['country']['name'], $adver['province']['name'], 
				$adver['city']['name'], $adver['address'], $adver['lang'], true)));
			/*$entry->addAuthor(array(
				'name'  => 'Paddy',
				'email' => 'paddy@example.com',
				'uri'   => 'http://www.example.com',
			));*/
			$entry->setDateModified((int)$adver['updated_at']);
			$entry->setDateCreated((int)$adver['created_at']);
			$entry->setDescription(\yii\helpers\StringHelper::truncate(strip_tags($adver['description']), 140));
			
			//$entry->setContent ($description);
			$feed->addEntry($entry);
		}
				
		return $feed->export($feedMethod);
		
	}
	
    public function getModel($limit = null, $cat = 0)
    {
        $advers = Adver::find()
					->with([
						'gallery' => function($query) {
							$query->select([
								'id',
								'adver_id',
								'name',
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
							'status' => Adver::STATUS_ACTIVE,
							'lang' => ['*', Yii::$app->language],
						])
						->orderBy([
							'id' => SORT_DESC
						])
						->limit($limit);
		if ($cat > 0){
			$advers->andWhere(['category_id' => $cat]);
		}
		return $advers->asArray()->all();
    }
}
