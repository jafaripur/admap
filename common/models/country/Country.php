<?php

namespace common\models\country;

use Yii;
use common\models\user\User;
use common\models\province\Province;
use common\models\adver\Adver;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Province[] $provinces
 * @property Adver[] $advers
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 64],
            ['user_id', 'default', 'value' => Yii::$app->getUser()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Author'),
            'name' => Yii::t('app', 'Country name'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }   
    
    public function getLatitudeLongitude()
    {
        return [
            (empty($this->latitude) ? 32.96256 : $this->latitude),
            (empty($this->longitude) ? 53.94828 : $this->longitude)
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinces()
    {
        return $this->hasMany(Province::className(), ['country_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvers()
    {
        return $this->hasMany(Province::className(), ['country_id' => 'id']);
    }
}
