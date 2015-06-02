<?php
namespace backend\models\user;

use yii\base\Model;
use Yii;

/**
 * Update password form
 */
class UpdatePasswordForm extends Model
{
    public $id;
    public $password;   
    public $confirmPassword;
    
    public $passwordUpdated = false;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirmPassword', 'id'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'confirmPassword' => Yii::t('app', 'Confirm Password'),
        ];
    }
    
    /**
     * Change Password
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updatePassword(\common\models\user\User $user)
    {
        if (!$this->validate()){
            return null;
        }
        
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($this->passwordUpdated = $user->save())
            return $user;
        
        return null;
    }
    
}
