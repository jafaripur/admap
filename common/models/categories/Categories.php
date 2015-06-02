<?php

namespace common\models\categories;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\user\User;
use common\models\adver\Adver;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $icon
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property User $user
 * @property Adver[] $advers
 */
class Categories extends \yii\db\ActiveRecord
{
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
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
            ['name', 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLE]],
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
            'name' => Yii::t('app', 'Category name'),
            'icon' => Yii::t('app', 'Icon'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'status' => Yii::t('app', 'Status'),
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
    public function getAdvers()
    {
        return $this->hasMany(Adver::className(), ['user_id' => 'id']);
    }
    
    public static function getStatusList()
    {
        return [
            (string)self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            (string)self::STATUS_DISABLE => Yii::t('app', 'Disabled'),
        ];
    }
        
    public function getStatusImage($status)
    {
        switch ($status)
        {
            case self::STATUS_ACTIVE:
                return '<i class="glyphicon glyphicon-ok text-success" title="'.Yii::t('app', 'Active').'"></i>';
                break;
            case self::STATUS_DISABLE:
                return '<i class="glyphicon glyphicon-remove text-danger" title="'.Yii::t('app', 'Disabled').'"></i>';
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
}
