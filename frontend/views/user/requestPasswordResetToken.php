<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */
/* @var $success boolean */
/* @var $message string */

$this->title = Yii::t('app', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;

$js ="
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    ajaxLoadHtml('{$model->formName()}', 'site-request-password-reset');
	return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);

?>
<div id= "site-request-password-reset" class="site-request-password-reset max-width-330">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title ?></h3>
                </div>
                <div class="panel-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= Html::encode($message) ?></div>
                <?php else: ?>
                    <?php if ($message != ''): ?>
                    <div class="alert alert-error"><?= Html::encode($message) ?></div>
                    <?php endif; ?>
                <?php $form = ActiveForm::begin([
                        'id' => $model->formName(),
                        //'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        //'validationUrl' => ['/user/ajax-request-reset-password-validation'],
                    ]); ?>
                    <?= $form->field($model, 'email') ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>