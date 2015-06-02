<?php


namespace backend\models\city;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\city\City;

class CitySearch extends City
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id'], 'integer'],
            [['name', 'user.username', 'province.name'], 'safe'],
        ];
    }
    
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'user.username',
            'province.name',
        ]);
    }

    public function search($params)
    {
        $query = City::find();
                
        // join with relation `author` that is a relation to the table `users`
        // and set the table alias to be `author`
        $query->joinWith([
            'user' => function($query){
                $query->from(['u' => 'user']);
            },
            'province' => function($query){
                $query->from(['p' => 'province']);
            },
        ]);
                
        $query->from(['c' => $this->tableName()]);
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 1),
        ]);
        
        $dataProvider->sort->attributes['user.username'] = [
            'asc' => ['u.username' => SORT_ASC],
            'desc' => ['u.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['province.name'] = [
            'asc' => ['p.name' => SORT_ASC],
            'desc' => ['p.name' => SORT_DESC],
        ];
        
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['c.id' => $this->id]);
        $query->andFilterWhere(['like', 'c.name', $this->name]);
        $query->andFilterWhere(['LIKE', 'u.username', $this->getAttribute('user.username')]);
        $query->andFilterWhere(['LIKE', 'p.name', $this->getAttribute('province.name')]);
        
        return $dataProvider;
    }
}