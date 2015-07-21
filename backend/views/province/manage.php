<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\province\Province;
use yii\web\View;
use common\widgets\Alert;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\province\Province */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Provinces'),
    'url' => ['/province/index']
];

if ($model->isNewRecord) {
    $this->title = Yii::t('app', 'New province');
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->title = Yii::t('app', 'Update information {province}',
            [
            'province' => Html::encode($model->name)
    ]);
    $this->params['breadcrumbs'][] = Html::encode($model->name);
}
$this->registerJsFile(Yii::$app->helper->getGoogleMapUrl(),
    [
    'position' => View::POS_HEAD,
]);

$js = "
var map;
var marker;
function initialize() {
    var defaultLatlng = new google.maps.LatLng(".implode(',',
        $model->getLatitudeLongitude()).");
    var mapOptions = {
        zoom: 5,
        center: defaultLatlng,
        panControl: false,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: false,
        overviewMapControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        }
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    marker = new google.maps.Marker({
        position: defaultLatlng,
        map: map,
        draggable: true
    });
    google.maps.event.addListener(map, 'click', function(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        $('#province-latitude').val(lat);
        $('#province-longitude').val(lng);
        var newDefaultLatlng = new google.maps.LatLng(lat, lng);
        marker.setPosition(newDefaultLatlng);
    });
    google.maps.event.addListener(marker, 'drag', function(event) {
        $('#province-latitude').val(event.latLng.lat());
        $('#province-longitude').val(event.latLng.lng());
    });
}

function changeMarkerPosition()
{
    var new_marker_position = new google.maps.LatLng($('#province-latitude').val(), $('#province-longitude').val());
    marker.setPosition(new_marker_position);
    map.setCenter(new_marker_position);
}

google.maps.event.addDomListener(window, 'load', initialize);
";
$this->registerJs($js, View::POS_HEAD);

$countryListUrl = Url::to(['/country/country-list']);
$initCountryListScript = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$countryListUrl}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
if (!$model->isNewRecord) {
    $userListUrl = Url::to(['/user/user-list']);
    $initUserListScript = <<< SCRIPT
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
?>
<div id="update-container" class="max-width-800">
    <div class="row">
        <div class="col-md-12">
            <?= Alert::widget() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo ($model->isNewRecord ? Yii::t('app',
                    'New country') : $model->name)
            ?></h3>
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
                    <?=
                    $form->field($model, 'latitude')->textInput([
                        'dir' => 'ltr',
                        'onchange' => 'changeMarkerPosition();',
                    ])
                    ?>
                    <?=
                    $form->field($model, 'longitude')->textInput([
                        'dir' => 'ltr',
                        'onchange' => 'changeMarkerPosition();',
                    ])
                    ?>
                    <?=
                    $form->field($model, 'country_id')->widget(Select2::classname(),
                        [
                        'language' => Yii::$app->helper->getTwoCharLanguage(),
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 2,
                            'ajax' => [
                                'url' => $countryListUrl,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                            ],
                            'initSelection' => new JsExpression($initCountryListScript)
                        ],
                    ]);
                    ?>
                    <?php
                    if (!$model->isNewRecord) {
                        echo $form->field($model, 'user_id')->widget(Select2::classname(),
                            [
                            //'options' => ['placeholder' => 'Search for a city ...'],
                            'language' => substr(Yii::$app->language, 0, 2),
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 2,
                                'ajax' => [
                                    'url' => $userListUrl,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                ],
                                'initSelection' => new JsExpression($initUserListScript)
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
        <div class="col-md-7">
            <div id="map" style="height: 24.5em"></div>
        </div>
    </div>
</div>