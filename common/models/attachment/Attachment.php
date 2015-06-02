<?php

namespace common\models\attachment;

use Yii;

use common\models\adver\Adver;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "attachment".
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
class Attachment extends \yii\db\ActiveRecord
{
    
    public $attachment;
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment';
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
			['attachment', 'file', 'maxSize' => Yii::$app->params['maxAttachmentSize'], 'extensions' => ['zip', 'rar', 'pdf', 'doc', 'docx', 'txt']],
            ['attachment', 'file', 'on' => 'new',  'skipOnEmpty' => false],
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
            'attachment' => Yii::t('app', 'attachment'),
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
        $attachmentPath = Attachment::getAttachmentPath($adverId) . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($attachmentPath)){
			@unlink($attachmentPath);
        }
    }
    
    public static function getAttachmentPath($adverId)
    {
        $path = Yii::getAlias('@common/assets/attachment/'.(string)((int)($adverId / 10000) + 1) . '/'.$adverId);
		$path = FileHelper::normalizePath($path);
        if (!is_file($path)){
            FileHelper::createDirectory($path);
        }
        return $path;
    }
	
	public function getAttachment()
    {
        return self::getAttachmentFromOutside($this->adver_id, $this->name);
    }
	
	public static function getAttachmentFromOutside($adver_id, $name)
    {
        $path = self::getAttachmentPath($adver_id);
		$path = $path . DIRECTORY_SEPARATOR . $name;
		if (file_exists($path)){
			return $path;
		}
		return false;
    }
	    
    public static function checkLimitation($adverId)
    {
        $limitAttachment = Yii::$app->params['maxAttachment'];
        $attachmentCount =(new \yii\db\Query())
                ->select('COUNT(id)')
                ->from('attachment')
                ->where([
                    'adver_id' => $adverId,
                ])
                ->scalar();
        
        if ($limitAttachment < $attachmentCount){
            return false;
        }
        
        return true;
    }
}
