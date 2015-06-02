<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$link = Yii::$app->urlManager->createAbsoluteUrl(['user/login']);
?>
<div
	<p>
	<?= Yii::t('app', 'Hello {username},',[
	'username' => Html::encode($user->username)
	]) ?>
	</p>
	<p><?= Yii::t('app', 'Your password changed and your new password is:') ?></p>
	<p><?= Yii::t('app', 'Password') ?>:&nbsp;<strong><?= Html::encode($user->getRawPassword()) ?></strong></p>
	<p><?= Yii::t('app', 'IP') ?>:&nbsp;<strong><?= Yii::$app->getRequest()->getUserIP() ?></strong></p>
	<p><?= Yii::t('app', 'Changed time') ?>:&nbsp;<strong><?= date('Y-m-d H:m:s') ?></strong></p>
	<p><?= Yii::t('app', 'For change username or password please visit to site and login with your account.') ?></p>
	<p><?= Html::a(Html::encode($link), $link) ?></p>
</div>