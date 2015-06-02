<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;

$js ="
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    ajaxLoadHtml('{$model->formName()}', 'login-container');
	return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);

?>
<div id="login-container" class="max-width-500">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title ?></h3>
                </div>
                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => $model->formName(),
                                //'enableAjaxValidation' => false,
                                'enableClientValidation' => true,
                    ]);
                    ?>
                    <?= $form->field($model, 'username')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'password')->passwordInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <div class="form-group">
                    <?= Html::a(Yii::t('app', 'Register'), ['/user/register'], [
                        'class' => 'btn btn-default btn-block fancybox fancybox.ajax',
                        'name' => 'register-button',
                        //'onclick' => "return ajaxModalClick('signupSigninModal', 'signupSigninModalContainer', '".Url::to(['/user/signup'])."');"
                    ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Yii::t('app', 'Login with'); ?></h3>
                </div>
                <div class="panel-body">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'id' => 'auth-choice', // Set up ID manually !
                    'baseAuthUrl' => ['user/auth'],
                    'options' => [
                        'style' => 'direction: ltr;',
                    ],
                    'popupMode' => false,
                ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="text-center">
                <?= Html::a(Yii::t('app', 'If you forgot your password you can reset it.'),
                    ['/user/request-password-reset-token'], [
                        //'onclick' => "return ajaxModalClick('signupSigninModal', 'signupSigninModalContainer', '".Url::to(['/user/request-password-reset-token'])."');"
                        'class' => 'fancybox fancybox.ajax'
                    ]) ?>
            </p>
        </div>
    </div>
</div>