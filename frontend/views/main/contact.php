<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('app', 'Contact with us');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= Alert::widget(); ?>
<p>
	<?= Yii::t('app', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'); ?>
</p>
<div class="row">
	<div class="col-lg-5">
		<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
			<?= $form->field($model, 'name') ?>
			<?= $form->field($model, 'email')->textInput(['dir' => 'ltr']); ?>
			<?= $form->field($model, 'subject') ?>
			<?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
			<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
				'captchaAction' => 'main/captcha',
				'options' => [
					'dir' => 'direction: ltr',
					'class' => 'form-control'
				],
				//'template' => '<div class="row" dir="ltr"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
			]) ?>
			<div class="form-group">
				<?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>