<?php

namespace common\models\province;

use Yii;
use common\models\user\User;
use common\models\country\Country;
use common\models\adver\Adver;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $country_id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City[] $cities
 * @property Country $country
 * @property User $user
 * @property Adver[] $advers
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'country_id'], 'integer'],
            [['name'], 'required'],
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
            'country' => Yii::t('app', 'Country'),
            'name' => Yii::t('app', 'Province name'),
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
    public function getCities()
    {
        //return $this->hasMany(City::className(), ['province' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
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
    public function getAdvers()
    {
        return $this->hasMany(Adver::className(), ['id' => 'user_id']);
    }
}
