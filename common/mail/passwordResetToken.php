<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>
<div>
	<p>
	<?= Yii::t('app', 'Hello {username},',[
		'username' => Html::encode($user->username)
	]) ?>
	</p>
	<p><?= Yii::t('app', 'Follow the link below to reset your password:') ?></p>
	<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>