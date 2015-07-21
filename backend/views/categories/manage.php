<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\categories\Categories;
use common\widgets\Alert;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\categories\Categories */

$this->title = $model->isNewRecord ? Yii::t('app', 'New category') : Html::encode($model->name);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Categories Manager'),
    'url' => ['/categories/index']
];
$this->params['breadcrumbs'][] = $this->title;
if (!$model->isNewRecord) {
    $userListUrl = Url::to(['/user/user-list']);
    $initScript = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$userListUrl}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
}
/* $js = <<<SCRIPT
  $('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
  ajaxObj = ajaxLoadHtml('{$model->formName()}', 'update-container');
  ajaxObj.complete(function(){
  reloadPjax('categoriesList-pjax');
  });
  return false;
  }).on('submit', function(e){
  e.preventDefault();
  });
  SCRIPT;
  $this->registerJs($js); */
?>
<div id="update-container" class="max-width-330">
    <div class="row">
        <div class="col-md-12">
            <?= Alert::widget() ?>
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
                    <?= Html::activeHiddenInput($model,
                        'id')
                    ?>
                    <?= $form->field($model, 'name')->textInput() ?>

                    <?php
                    if (Yii::$app->getUser()->can('CategoryDisable')) {
                        echo $form->field($model, 'status')->dropDownList(Categories::getStatusList());
                    }
                    ?>
                    <?php
                    if (!$model->isNewRecord) {
                        echo $form->field($model, 'user_id')->widget(Select2::classname(),
                            [
                            //'options' => ['placeholder' => 'Search for a city ...'],
                            'language' => Yii::$app->helper->getTwoCharLanguage(),
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 2,
                                'ajax' => [
                                    'url' => $userListUrl,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                ],
                                'initSelection' => new JsExpression($initScript)
                            ],
                        ]);
                    }
                    ?>
                    <div class="form-group">
                        <?=
                        Html::submitButton(($model->isNewRecord ? Yii::t('app',
                                    'Add') : Yii::t('app', 'Update')),
                            ['class' => 'btn btn-primary btn-block'])
                        ?>
                    </div>
<?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>