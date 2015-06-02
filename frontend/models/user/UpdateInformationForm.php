<?php
namespace frontend\models\user;

use yii\base\Model;
use Yii;
use common\models\user\User;

/**
 * Update information form
 */
class UpdateInformationForm extends Model
{
    public $username;
    
    public $informationUpdated = false;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'checkUsernameExists'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            
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
            if (!User::checkUsernameExist(Yii::$app->getUser()->id, $this->username)) {
                $this->addError($attribute, Yii::t('app', 'This username has already been taken.'));
            }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
        ];
    }
    
    public function setUserData(User $user)
    {
        $this->username = $user->username;
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
        $user->username = $this->username;
        if ($this->informationUpdated = $user->save())
            return $user;
        
        return null;
    }
    
}