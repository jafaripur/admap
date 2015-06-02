<?php


namespace backend\models\province;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\province\Province;

class ProvinceSearch extends Province
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id'], 'integer'],
            [['name', 'user.username', 'country.name'], 'safe'],
        ];
    }
    
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'user.username',
            'country.name',
        ]);
    }

    public function search($params)
    {
        $query = Province::find();
                
        // join with relation `author` that is a relation to the table `users`
        // and set the table alias to be `author`
        $query->joinWith([
            'user' => function($query){
                $query->from(['u' => 'user']);
            },
            'country' => function($query){
                $query->from(['c' => 'country']);
            },
        ]);
                
        $query->from(['p' => $this->tableName()]);
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 1),
        ]);
        
        $dataProvider->sort->attributes['user.username'] = [
            'asc' => ['u.username' => SORT_ASC],
            'desc' => ['u.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['country.name'] = [
            'asc' => ['c.name' => SORT_ASC],
            'desc' => ['c.name' => SORT_DESC],
        ];
        
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['p.id' => $this->id]);
        $query->andFilterWhere(['like', 'p.name', $this->name]);
        $query->andFilterWhere(['LIKE', 'u.username', $this->getAttribute('user.username')]);
        $query->andFilterWhere(['LIKE', 'c.name', $this->getAttribute('country.name')]);
        
        return $dataProvider;
    }
}