<?php
namespace frontend\models\user;

use common\models\user\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    
    public $confirmPassword;
    public $confirmEmail;
    
    public $verifyCode;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\user\User', 'message' => Yii::t('app', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => Yii::t('app', 'This email address has already been taken.')],
            ['confirmEmail', 'compare', 'compareAttribute' => 'email'],
            
            [['password', 'confirmPassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha', 'captchaAction' => 'user/captcha', 'caseSensitive' => false,],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'confirmEmail' => Yii::t('app', 'Confirm email'),
            'password' => Yii::t('app', 'Password'),
            'confirmPassword' => Yii::t('app', 'Confirm Password'),
            'verifyCode' => Yii::t('app', 'Verification Code'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register($validate = true)
    {
        if ($validate && !$this->validate()){
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->save();
        return $user;
    }
    
}
