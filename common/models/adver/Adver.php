<?php

namespace common\models\adver;

use Yii;

use common\models\user\User;
use common\models\country\Country;
use common\models\province\Province;
use common\models\city\City;
use common\models\categories\Categories;
use common\models\gallery\Gallery;
use common\models\attachment\Attachment;
use yii\helpers\FileHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\Html;

/**
 * This is the model class for table "adver".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $country_id
 * @property integer $province_id
 * @property integer $city_id
 * @property string $title
 * @property string $description
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $lang
 *
 * @property User $user
 * @property Province $province
 * @property City $city
 * @property Country $country
 * @property Categories $category
 * @property Gallery $gallery
 * @property Attachment $attachment
 */
class Adver extends \yii\db\ActiveRecord
{
    
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;
   	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%adver}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_DISABLE],
			['lang', 'default', 'value' => Yii::$app->language],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLE]],
            ['user_id', 'default', 'value' => Yii::$app->getUser()->id],
            [['category_id', 'country_id', 'province_id', 'city_id'], 'safe'],
            [['title', 'description', 'address', 'latitude', 'longitude'], 'required'],
            [['description'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['title', 'address'], 'string', 'max' => 250]
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
            'category_id' => Yii::t('app', 'Category name'),
            'country_id' => Yii::t('app', 'Country name'),
            'province_id' => Yii::t('app', 'Province name'),
            'city_id' => Yii::t('app', 'City name'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
			'lang' => Yii::t('app', 'Language'),
        ];
    }
    
    public function afterDelete() {
        parent::afterDelete();
        self::deleteDependentFiles($this->id);
    }
	
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);
		
	}
    
    public function getLatitudeLongitude()
    {
        return [
            (empty($this->latitude) ? 32.96256 : Html::encode($this->latitude)),
            (empty($this->longitude) ? 53.94828 : Html::encode($this->longitude))
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
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
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
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasMany(Gallery::className(), ['adver_id' => 'id']);
    }
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasMany(Attachment::className(), ['adver_id' => 'id']);
    }
    
    public function getStatusImage($status)
    {
        switch ($status)
        {
            case self::STATUS_ACTIVE:
                return '<i class="glyphicon glyphicon-ok text-success" title="'.Yii::t('app', 'Active').'"></i>';
                break;
            case self::STATUS_DISABLE:
                return '<i class="glyphicon glyphicon-remove text-danger" title="'.Yii::t('app', 'Disable').'"></i>';
                break;
            default :
                return '';
        }
    }
	
	public function getStatusButton($status, $url, $permission, $pjaxGridName, $messageContainer)
    {
        switch ($status)
        {
            case self::STATUS_ACTIVE:
			{
				if ($permission != '' && !Yii::$app->getUser()->can($permission))
					return '<i class="glyphicon glyphicon-ok text-success" title="'.Yii::t('app', 'Active').'"></i>';
				return Html::a('<i class="glyphicon glyphicon-ok text-success"></i>', '#', [
                    'title' => Yii::t('app', 'Disable'),
					"onclick" => "return disableGridButton('{$url}', '{$pjaxGridName}', '{$messageContainer}' );",
                ]);
                break;
			}
            case self::STATUS_DISABLE:
			{
				if ($permission != '' && !Yii::$app->getUser()->can($permission))
					return '<i class="glyphicon glyphicon-remove text-danger" title="'.Yii::t('app', 'Disabled').'"></i>';
				return Html::a('<i class="glyphicon glyphicon-remove text-danger"></i>', '#', [
                    'title' => Yii::t('app', 'Enable'),
					"onclick" => "return disableGridButton('{$url}', '{$pjaxGridName}', '{$messageContainer}' );",
                ]);
				
                break;
			}
            default :
                return '';
        }
    }
    
    public static function getStatusList()
    {
        return [
            (string)self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            (string)self::STATUS_DISABLE => Yii::t('app', 'Disable'),
        ];
    }
        
    public static function deleteDependentFiles($adverId){
        $galleryPath = Gallery::getImagePath($adverId);
		$attachmentPath = Attachment::getAttachmentPath($adverId);
        if (is_dir($galleryPath)){
			FileHelper::removeDirectory($galleryPath);
        }
		if (is_dir($attachmentPath)){
			FileHelper::removeDirectory($attachmentPath);
        }
    }
    
    Public static function getUserIdFromAdver($id)
    {
        return (new Query())
                ->select('[[user_id]]')
                ->from('{{%adver}}')
                ->where([
                    '[[id]]' => $id,
                ])
                ->scalar();
    }
	
	public static function generateLink($id, $title, $category, $country, $state, $city, $address, $lang, $absolute = false){
		if ($lang === 'fa-IR' || $lang === '*'){
			return \yii\helpers\Url::to([
				'/adver/view',
				'id' => $id,
				'title' => trim(Yii::$app->helper->normalizeTextForUrl($title.'-'.$category.'-'.$country.'-'.$state.'-'.$city.'-'.$address), '-'),
			], $absolute);		
		}
		
		return \yii\helpers\Url::to([
			'/adver/view',
			'id' => $id,
			'language' => $lang,
			'title' => trim(Yii::$app->helper->normalizeTextForUrl($title.'-'.$category.'-'.$country.'-'.$state.'-'.$city.'-'.$address), '-'),
		], $absolute);	
	}
		
	protected function getClusteringMarkers($zoom, $lat_max, $lat_min, $lng_min, $lng_max){
		$number = 0;
		
		$multiplyNumber = (1 / 500) * pow(2.4, $zoom);
        
		$query = new Query();

		$query->from('{{%adver}}')
			->select([
				"COUNT(*) AS [[adver_count]]",
				"AVG(latitude) AS [[lat]]",
				"AVG(longitude) AS [[lng]]",
				"FORMAT([[latitude]] * {$multiplyNumber} , $number) AS [[g_lt]]",
				"FORMAT([[longitude]] * {$multiplyNumber} , $number) AS [[g_ln]]",
			]);
		
		$query->andWhere([
			'between', '[[latitude]]', $lat_min, $lat_max
		]);
		$query->andWhere([
			'between', '[[longitude]]', $lng_min, $lng_max
		]);
		$query->WHERE([
			'[[status]]' => self::STATUS_ACTIVE,
			'lang' => ['*', Yii::$app->language],
		]);
		
        // adjust the query by adding the filters

		$query->andFilterWhere(['[[category_id]]' => $this->category_id]);
		$query->andFilterWhere(['[[country_id]]' => $this->country_id]);
		$query->andFilterWhere(['[[province_id]]' => $this->province_id]);
		$query->andFilterWhere(['[[city_id]]' => $this->city_id]);
		$query->andFilterWhere(['like', '[[title]]', $this->title]);
		$query->andFilterWhere(['like', '[[address]]', $this->address]);
        
		$query->groupBy(['[[g_lt]]', '[[g_ln]]']);
		return $this->mergeBubbles($zoom, $query->all());
	}
	protected function mergeBubbles($zoom, $data, $a = 1.1) {
        $data2 = $data;
        $minRadius = $a / (pow(2, $zoom));
		$i = 0;
        foreach ($data as $key => $value) {
			unset($data[$key]['g_ln']);
			unset($data[$key]['g_lt']);
            foreach ($data2 as $key2 => $value2) {
				$i++;
                if ($key == $key2) {
					unset($data2[$key2]);
                    continue;
                }

                $result = acos(cos(deg2rad($value['lat'])) * cos(deg2rad($value2['lat'])) * cos(deg2rad($value2['lng']) - deg2rad($value['lng'])) + sin(deg2rad($value['lat'])) * sin(deg2rad($value2['lat'])));

                if ($result < $minRadius) {
					$data[$key] = [
						'adver_count' => $data[$key]['adver_count'] + $data[$key2]['adver_count'],
						'lat' => ($data[$key]['lat'] + $data[$key2]['lat']) / 2,
						'lng' => ($data[$key]['lng'] + $data[$key2]['lng']) / 2,
					];
					unset($data2[$key2]);
					unset($data[$key2]);
                }
            }
        }
        rsort($data);
        return $data;
    }
	
	protected function getMarkers($lat_max, $lat_min, $lng_min, $lng_max){
		
		$query = new Query();

		$query->from('{{%adver}}')
			->select([
				'[[id]]',
				'[[title]]',
				"latitude AS [[lat]]",
				"longitude AS [[lng]]",
			]);
				
		$query->andWhere([
			'between', '[[latitude]]', $lat_min, $lat_max
		]);
		$query->andWhere([
			'between', '[[longitude]]', $lng_min, $lng_max
		]);
		$query->where([
			'[[status]]' => self::STATUS_ACTIVE,
			'lang' => ['*', Yii::$app->language],
		]);
		
        // adjust the query by adding the filters

		$query->andFilterWhere(['[[category_id]]' => $this->category_id]);
		$query->andFilterWhere(['[[country_id]]' => $this->country_id]);
		$query->andFilterWhere(['[[province_id]]' => $this->province_id]);
		$query->andFilterWhere(['[[city_id]]' => $this->city_id]);
		$query->andFilterWhere(['like', '[[title]]', $this->title]);
		$query->andFilterWhere(['like', '[[address]]', $this->address]);
        
		$advers = $query->all();
		
		$uniques = [];
		$duplicate = [];
		$uq_advers = [];
		foreach($advers as $adver){
			$adver['lat'] = Yii::$app->helper->formatLatLng($adver['lat']);
			$adver['lng'] = Yii::$app->helper->formatLatLng($adver['lng']);
			$latlng = $adver['lat']."+".$adver['lng'];
			if(in_array($latlng, $uniques))
			{
				$duplicate[$latlng][] = $adver['id'];
				continue;
			}
			$uniques[] = $latlng;
			$uq_advers[] = $adver;
		}
		$markers = array();
		
		foreach ($uq_advers as $key => $adver)
		{
			$latlng = $adver['lat']."+".$adver['lng'];
			$count = isset($duplicate[$latlng]) ? count($duplicate[$latlng]) : 0;
			$ids = trim($adver['id'].'-'.($count > 0 ? implode('-', $duplicate[$latlng]) : ''), '-');
			//$markers[$key]['id'] = $adver['id'];
			$markers[$key]['lat'] = $adver['lat'];
			$markers[$key]['lng'] = $adver['lng'];
			$markers[$key]['duplicate'] = $count;
			$markers[$key]['ids'] = $ids;
			$markers[$key]['title'] = $count == 0 ? Html::encode($adver['title']) : '';			
		}
				
		return $markers;
	}
}
