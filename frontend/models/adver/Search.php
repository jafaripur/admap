<?php


namespace frontend\models\adver;

use Yii;
use common\models\adver\Adver;
use yii\data\ActiveDataProvider;

class Search extends Adver
{
    
	public function __construct($params) {
		parent::__construct();
		$this->load($params);
	}
	
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['category_id', 'country_id', 'province_id', 'city_id'], 'integer'],
            [['title', 'address'], 'safe'],
        ];
    }
	
	public function search($params){
		$query = Adver::find()
			->with([
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
					'status' => Adver::STATUS_ACTIVE,
					'lang' => ['*', Yii::$app->language],
				]);
		
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => array('pageSize' => 10),
			//'sort' => ['attributes' => ['id', 'title']]
		]);
		
		/*$dataProvider->setSort([
			'attributes' => [
				'id' => [
					'asc' => ['id' => SORT_ASC],
					'desc' => ['id' => SORT_DESC],
					'label' => Yii::t('app', 'ID'),
					//'default' => SORT_ASC
				],
			]
		]);*/
		
		// load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
		$query->andFilterWhere(['category_id' => $this->category_id]);
		$query->andFilterWhere(['country_id' => $this->country_id]);
		$query->andFilterWhere(['province_id' => $this->province_id]);
		$query->andFilterWhere(['city_id' => $this->city_id]);
		$query->andFilterWhere(['title' => $this->title]);
		$query->andFilterWhere(['address' => $this->address]);
		
		return $dataProvider;
	}
    
    public function searchCluster($zoom, $lat_max, $lat_min, $lng_min, $lng_max)
    {
		return $this->getClusteringMarkers($zoom, $lat_max, $lat_min, $lng_min, $lng_max);
    }
	public function searchMarker($lat_max, $lat_min, $lng_min, $lng_max)
    {				
		return $this->getMarkers($lat_max, $lat_min, $lng_min, $lng_max);
    }
}