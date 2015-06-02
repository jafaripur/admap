<?php
namespace common\models\user;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\categories\Categories;
use common\models\country\Country;
use common\models\province\Province;
use common\models\city\City;
use common\models\adver\Adver;
use yii\helpers\Html;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * 
 * @property Adver[] $advers
 * @property Categories[] $categories
 * @property Country[] $countries
 * @property City[] $cities
 * @property Province[] $provinces
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;
    
    private $rawPassword;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLE]],
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return $fields;
    }
    
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinces()
    {
        return $this->hasMany(Province::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvers()
    {
        return $this->hasMany(Adver::className(), ['user_id' => 'id']);
    }
            
    /*
    // explicitly list every field, best used when you want to make sure the changes
    // in your DB table or model attributes do not cause your field changes (to keep API backward compatibility).
    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',

            // field name is "email", the corresponding attribute name is "email_address"
            'email' => 'email_address',

            // field name is "name", its value is defined by a PHP callback
            'name' => function () {
                return $this->first_name . ' ' . $this->last_name;
            },
        ];
    }
    */
    
    public function afterSave($insert, $changedAttributes) {
        if ($insert){
            $this->sendRegistrationWelcomeEmail();
        }
        parent::afterSave($insert, $changedAttributes);
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
                return '<i class="glyphicon glyphicon-remove text-danger" title="'.Yii::t('app', 'Deleted').'"></i>';
                break;
            default :
                return '';
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        $id = (int)explode('_', $token)[0];
        $user = static::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE,
        ]);
        if ($user && $user->password_reset_token == $token){
            return $user;
        }
        
        return null;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->rawPassword = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = $this->id . '_' . Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function login($remember)
    {
        /*Yii::$app->user->on(\yii\web\User::EVENT_BEFORE_LOGIN, function($event){
            
        });*/
        
        return Yii::$app->user->login($this, $remember ? 3600 * 24 * 30 : 0);
    }
    
    public function sendRegistrationWelcomeEmail() {
        if(Yii::$app->params['sendRegistrationWelcomeEmail']) {
            return Yii::$app->mailer->compose('registrationWelcome', ['user' => $this])
                            ->setFrom([Yii::$app->params['noReply'] => Yii::$app->name . ' robot'])
                            ->setTo($this->email)
                            ->setSubject(Yii::t('app', 'Welcome to {name}', [
                                        'name' => Yii::$app->name,
                            ]))
                            ->send();
        }
    }

    public function sendPasswordChangedEmail() {
        if(Yii::$app->params['sendPasswordChangedEmail']) {
            return Yii::$app->mailer->compose('passwordChanged', ['user' => $this])
                            ->setFrom([Yii::$app->params['noReply'] => Yii::$app->name . ' robot'])
                            ->setTo($this->email)
                            ->setSubject(Yii::t('app', 'Your password changed {name}', [
                                'name' => Yii::$app->name
                            ]))
                            ->send();
        }
    }
    
    public static function checkUsernameExist($id, $username)
    {
        $extractedId = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['username' => $username])
                ->scalar();
        if($extractedId){
            return $id == $extractedId;
        }
        
        return true;
    }
    public static function checkEmailExist($id, $email)
    {
        $extractedId = (new \yii\db\Query())
                ->select('id')
                ->from('user')
                ->where(['email' => $email])
                ->scalar();

        if($extractedId){
            return $id == $extractedId;
        }
        
        return true;
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