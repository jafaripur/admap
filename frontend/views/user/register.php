<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = $this->title;

$js ="
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    ajaxLoadHtml('{$model->formName()}', 'register-container');
	return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);

?>
<div id="register-container" class="max-width-500">
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
                                //'enableAjaxValidation' => true,
                                //'validateOnSubmit' => true,
                                //'validateOnChange' => false,
                                //'validateOnBlur' => false,
                                'enableClientValidation' => true,
                                //'validationUrl' => ['/user/ajax-register-validation'],
                    ]);
                    ?>
                    <?= $form->field($model, 'username')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'email')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'confirmEmail')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'password')->passwordInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'confirmPassword')->passwordInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'captchaAction' => 'user/captcha',
                        'options' => [
                            'dir' => 'direction: ltr',
                            'class' => 'form-control'
                        ],
                        //'template' => '<div class="row" dir="ltr"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>
                    <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Register'), ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Yii::t('app', 'Registered with'); ?></h3>
                </div>
                <div class="panel-body">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/user/auth'],
                    'options' => [
                        'style' => 'direction: ltr',
                    ],
                    'popupMode' => false,
                ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="text-center"><?= Html::a(Yii::t('app', 'Already registered? Sign in!'), ['/user/login'], [
                //'onclick' => "return ajaxModalClick('signupSigninModal', 'signupSigninModalContainer', '".Url::to(['/user/login'])."');"
                'class' => 'fancybox fancybox.ajax'
            ]) ?></p>
        </div>
    </div>
</div>