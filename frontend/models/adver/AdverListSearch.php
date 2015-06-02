<?php


namespace frontend\models\adver;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\adver\Adver;

class AdverListSearch extends Adver
{
        
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status'], 'integer'],
            [['title', 'lang', 'category.name', 'country.name', 'province.name', 'city.name'], 'safe'],
        ];
    }
    
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'category.name',
            'country.name',
            'province.name',
            'city.name',
        ]);
    }

    public function search($params)
    {
        $query = Adver::find();
        
        $query->joinWith([
            'category' => function($query){
                $query->from(['c' => 'categories']);
                
            },
            'country' => function($query){
                $query->from(['co' => 'country']);
                
            },
            'province' => function($query){
                $query->from(['p' => 'province']);
                
            },
            'city' => function($query){
                $query->from(['ci' => 'city']);
                
            },
        ]);
        
        $query->from(['a' => $this->tableName()]);
        $query->andWhere(['a.user_id' => Yii::$app->getUser()->id]);
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 1),
        ]);
        
        $dataProvider->sort->attributes['category.name'] = [
            'asc' => ['c.name' => SORT_ASC],
            'desc' => ['c.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['country.name'] = [
            'asc' => ['co.name' => SORT_ASC],
            'desc' => ['co.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['province.name'] = [
            'asc' => ['p.name' => SORT_ASC],
            'desc' => ['p.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['city.name'] = [
            'asc' => ['ci.name' => SORT_ASC],
            'desc' => ['ci.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->defaultOrder = [
			'status' => SORT_ASC,
			'id' => SORT_DESC
		];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['a.id' => $this->id]);
        $query->andFilterWhere(['like', 'a.title', $this->title])
                ->andFilterWhere(['a.status' => $this->status])
				->andFilterWhere(['like', 'a.lang', $this->lang]);
        $query->andFilterWhere(['LIKE', 'c.name', $this->getAttribute('category.name')]);
        $query->andFilterWhere(['LIKE', 'co.name', $this->getAttribute('country.name')]);
        $query->andFilterWhere(['LIKE', 'p.name', $this->getAttribute('province.name')]);
        $query->andFilterWhere(['LIKE', 'ci.name', $this->getAttribute('city.name')]);
                
        return $dataProvider;
    }
}