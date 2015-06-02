<?php


namespace backend\models\country;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\country\Country;

class CountrySearch extends Country
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id'], 'integer'],
            [['name', 'user.username'], 'safe'],
        ];
    }
    
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'user.username'// => Yii::t('app', 'Username'),
        ]);
    }

    public function search($params)
    {
        $query = Country::find();
                
        // join with relation `author` that is a relation to the table `users`
        // and set the table alias to be `author`
        $query->joinWith(['user' => function($query){
            $query->from(['u' => 'user']);
        }]);

        $query->from(['c' => $this->tableName()]);
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 1),
        ]);
        
        $dataProvider->sort->attributes['user.username'] = [
            'asc' => ['u.username' => SORT_ASC],
            'desc' => ['u.username' => SORT_DESC],
        ];
        
        //$dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['c.id' => $this->id]);
        $query->andFilterWhere(['like', 'c.name', $this->name]);
        $query->andFilterWhere(['LIKE', 'u.username', $this->getAttribute('user.username')]);
        
        return $dataProvider;
    }
}