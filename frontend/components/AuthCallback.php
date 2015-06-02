<?php

/**
 * 
 * This class run after social login or social registration completed.
 * 
 * @author A.Jafaripur <mjafaripur@yahoo.com>
 * 
 */

namespace frontend\components;

use Yii;
use common\models\user\User;
use frontend\models\user\RegisterForm;

class AuthCallback
{
	/**
	 * Trigger after the social login or registration finished.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param yii\authclient\BaseClient $client Object which contain result from OAuth or OpenId
	 * @return yii\web\Response redirect to home page.
	 */
    public static function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $service = $client->getName();
        $username = '';
        $email = '';
		
        if ($service == 'github')
        {
            $username = $attributes['login'];
            $email = $attributes['email'];
        }
        elseif ($service == 'linkedin'){
            $email = $username = $attributes['email'];
		}
        elseif ($service == 'google'){
            $email = $username = $attributes['emails'][0]['value']; // for OAuth
        }
        elseif ($service == 'facebook'){
            $email = $username = $attributes['email'];
		}
		elseif ($service == 'live'){
            $email = $username = $attributes['emails']['preferred'];
		}

        $user = User::find()
                ->where(['username' => [$username, $email]])
                ->orWhere(['email' => $email])
                ->one();
        
        if (!$user){
            $model = new RegisterForm();
            $model->username = $username;
            $model->email = $email;
            $model->password = Yii::$app->getSecurity()->generateRandomString(8);
            $user = $model->register(false);
        }
        
        if ($user) {
            if ($user->login(false)) {
                return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
            }
        }
    }
}
