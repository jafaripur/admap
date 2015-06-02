<?php
namespace backend\models\user;

use yii\base\Model;
use Yii;
use common\models\user\User;

/**
 * Update information form
 */
class UpdateInformationForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    
    public $informationUpdated = false;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            ['id', 'required'],
            ['status', 'required'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'checkUsernameExists'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'checkEmailExists'],
            
        ];
    }
    
    /**
     * Check the username exist or not
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkUsernameExists($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!User::checkUsernameExist($this->id, $this->username)) {
                $this->addError($attribute, Yii::t('app', 'This username has already been taken.'));
            }
        }
    }
    
    /**
     * Check the username exist or not
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkEmailExists($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!User::checkEmailExist($this->id, $this->email)) {
                $this->addError($attribute, Yii::t('app', 'This email address has already been taken.'));
            }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    
    public function setUserData(User $user)
    {
        $this->id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->status = $user->status;
    }
    
    /**
     * Update information
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updateInformation(User $user)
    {
        if (!$this->validate()){
            return null;
        }
        $user->email = $this->email;
        $user->username = $this->username;
        $user->status = $this->status;
        if ($this->informationUpdated = $user->save())
            return $user;
        
        return null;
    }
    
}