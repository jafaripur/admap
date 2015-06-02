<?php


namespace backend\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

class UserSearch extends User
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();
                
        $query->select([
            'id',
            'username',
            'email',
            'created_at',
            'updated_at',
            'status'
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 3),
        ]);
        

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}