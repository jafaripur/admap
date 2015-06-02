<?php
namespace frontend\models\user;

use yii\base\Model;
use Yii;
use common\models\user\User;

/**
 * Update password form
 */
class UpdatePasswordForm extends Model
{
    public $oldPassword;
    public $password;   
    public $confirmPassword;
    
    public $passwordUpdated = false;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirmPassword', 'oldPassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            ['oldPassword', 'validatePassword'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'oldPassword' => Yii::t('app', 'Old password'),
            'password' => Yii::t('app', 'New password'),
            'confirmPassword' => Yii::t('app', 'Confirm Password'),
        ];
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(Yii::$app->getUser()->id);
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect old password.'));
            }
        }
    }
    
    /**
     * Change Password
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updatePassword(User $user)
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
