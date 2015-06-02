<?php


namespace common\models\attachment;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\attachment\Attachment;

class AttachmentSearch extends Attachment
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['title'], 'safe'],
        ];
    }

    public function search($params, $adverId)
    {
        $query = Attachment::find();

        $query->andWhere(['adver_id' => $adverId]);
        
        if (!Yii::$app->getUser()->can('GalleryList')){
            $query->andWhere(['user_id' => Yii::$app->getUser()->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => array('pageSize' => 1),
        ]);
               
        //$dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];
                
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'title', $this->title]);
                
        return $dataProvider;
    }
}