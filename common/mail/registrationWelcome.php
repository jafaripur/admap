<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$link = Yii::$app->urlManager->createAbsoluteUrl(['user/login']);
?>
<div>
	<p><?= Yii::t('app', 'Hello, This is your registration information:') ?></p>
	<p><?= Yii::t('app', 'Username') ?>:&nbsp;<strong><?= Html::encode($user->username) ?></strong></p>
	<p><?= Yii::t('app', 'Email') ?>:&nbsp;<strong><?= Html::encode($user->email) ?></strong></p>
	<p><?= Yii::t('app', 'Password') ?>:&nbsp;<strong><?= Html::encode($user->getRawPassword()) ?></strong></p>
	<p><?= Yii::t('app', 'For change username or password please visit to site and login with your account.') ?></p>
	<p><?= Html::a(Html::encode($link), $link) ?></p>
</div>