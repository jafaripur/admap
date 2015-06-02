<?php

namespace common\models\gallery;

use Yii;

use common\models\adver\Adver;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "gallery".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $adver_id
 * @property string $name
 * @property string $title
 * @property integer $index
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Adver $adver
 */
class Gallery extends \yii\db\ActiveRecord
{
    
    public $image;
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
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
			['image', 'file', 'maxSize' => Yii::$app->params['maxGalleryImageSize'], 'extensions' => ['jpg', 'png'], 'mimeTypes' => ['image/jpeg', 'image/png']],
            ['image', 'file', 'on' => 'new',  'skipOnEmpty' => false],
            ['adver_id', 'required'],
            [['adver_id', 'index'], 'integer'],
            [['name', 'title'], 'string', 'max' => 255],
            ['user_id', 'default', 'value' => Yii::$app->getUser()->id],
            ['index', 'default', 'value' => '0'],
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
            'adver_id' => Yii::t('app', 'Advertisement name'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'index' => Yii::t('app', 'Index'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'image' => Yii::t('app', 'Image'),
        ];
    }
	
	public function afterDelete() {
        parent::afterDelete();
        self::deleteDependentFiles($this->adver_id, $this->name);
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
    public function getAdver()
    {
        return $this->hasOne(Adver::className(), ['id' => 'adver_id']);
    }
	
	public static function deleteDependentFiles($adverId, $fileName){
        $galleryPath = Gallery::getImagePath($adverId) . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($galleryPath)){
			@unlink($galleryPath);
        }
    }
    
    public static function getImagePath($adverId)
    {
        $path = Yii::getAlias('@common/assets/images/gallery/'.(string)((int)($adverId / 10000) + 1) . '/'.$adverId);
		$path = FileHelper::normalizePath($path);
        if (!is_dir($path)){
            FileHelper::createDirectory($path);
        }
        return $path;
    }
    
    public function getImageUrl($width = 0, $heigh = 0, $quality = 70)
    {
        return self::getImageUrlFromOutside($this->name, $this->adver_id, $width, $heigh, $quality);
    }
    
    public static function getImageUrlFromOutside($name, $adverId, $width = 0, $heigh = 0, $quality = 70, $absolute = false)
    {
        $path = self::getImagePath($adverId) . DIRECTORY_SEPARATOR . $name;
        return ($absolute ? Yii::$app->getRequest()->getHostInfo() : '') . Yii::$app->imageHelper->getImage($adverId, $path, $width, $heigh, $quality);
    }
    
    public static function checkLimitation($adverId)
    {
        $limitGallery = Yii::$app->params['maxGalleryImage'];
        $imageCount =(new \yii\db\Query())
                ->select('COUNT(id)')
                ->from('gallery')
                ->where([
                    'adver_id' => $adverId,
                ])
                ->scalar();
        
        if ($limitGallery < $imageCount){
            return false;
        }
        
        return true;
    }
}
