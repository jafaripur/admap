<?php

use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\country\Country;
use common\models\province\Province;
use common\models\city\City;
use kartik\widgets\DepDrop;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

function getViewButton($mode)
{
    return '
	<div class="row">
		<div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px;">
			<div class="btn-group" role="group" aria-label="...">
			<button type="button" class="btn btn-info'.($mode === 'grid' ? ' active' : '').'" onclick="changeAdverView(\'grid\');"><span class="glyphicon glyphicon-th-list"></span> '.Yii::t('app',
            'List View').'</button>
			<button type="button" class="btn btn-info'.($mode === 'map' ? ' active' : '').'" onclick="changeAdverView(\'map\');"><span class="glyphicon glyphicon-map-marker"></span> '.Yii::t('app',
            'Map View').'</button>
			</div>
		</div>
	</div>';
}
$this->title = Yii::t('app',
        'Find business in nearest you! - Introduce your business!');
$this->registerJsFile(Yii::$app->helper->getGoogleMapUrl(),
    [
    'position' => View::POS_HEAD,
]);
$this->registerJsFile(Yii::$app->getRequest()->getBaseUrl().'/map/richmarker.min.js',
    [
    'position' => View::POS_HEAD,
]);
$adverView = Yii::$app->getRequest()->get('view', 'grid');
$clusterSearchUrl = Url::to(['/adver/search-cluster']);
$markerSearchUrl = Url::to(['/adver/search-marker']);
$infoWindowUrl = Url::to(['/adver/info-window']);
$mapZoom = Html::encode(Yii::$app->getRequest()->get('zoom', 5));
$latitude = Html::encode(Yii::$app->getRequest()->get('latitude', '32.96256'));
$longitude = Html::encode(Yii::$app->getRequest()->get('longitude', '53.94828'));
$markerImageUrl = Yii::$app->getRequest()->getBaseUrl().'/map/marker.png';
$js = <<< script
var adverObj = {
	markerAjaxObj: null,
	infoWindowAjaxObj: null,
	markersArray: new Array(),
	map: null,
	markerImageUrl: '{$markerImageUrl}',
	disableZoom: false,
	infoWindow: null,
	clusterSearchUrl: '{$clusterSearchUrl}',
	markerSearchUrl: '{$markerSearchUrl}',
	infoWindowUrl: '{$infoWindowUrl}'
};
function initializeAdverMap() {
	if (adverObj.map !== null){
		loadAdversSlaveMarker(false, false);
		return false;
	}
    var mapOptions = {
      zoom: {$mapZoom},
      center: new google.maps.LatLng({$latitude}, {$longitude}),
      panControl: false,
      scaleControl: true,
      streetViewControl: false,
      overviewMapControl: false
    };
    adverObj.map = new google.maps.Map(document.getElementById('map'), mapOptions);
	adverObj.infoWindow = new google.maps.InfoWindow();
	google.maps.event.addListenerOnce(adverObj.map, 'idle', function(){
		loadAdversSlaveMarker(false, false);
	});
	google.maps.event.addListener(adverObj.map, 'zoom_changed', function(){
		if (!adverObj.disableZoom){
			loadAdversSlaveMarker(true, false);
		}
	});
}
function loadAdversSlaveMarker(pushState, geolocating){
	loadAdvers(pushState, geolocating, 'form#{$searchModel->formName()}', adverObj);
}
script;
if ($adverView === 'map') {
    $js .= "google.maps.event.addDomListener(window, 'load', initializeAdverMap);";
}
$this->registerJs($js, View::POS_HEAD);

$js = <<< script
$('form#{$searchModel->formName()}').on('beforeSubmit', function(e, \$form) {
	var current = $('#hiddenViewMode').val();
	var state = "?" + $(this).serialize();
	window.history.replaceState({}, null, state);
	if (current === 'grid'){
		reloadPjax('adverList-pjax');
	}
	else if (current === 'map'){
		loadAdversSlaveMarker(false, true);
	}
	return false;
}).on('submit', function(e){
    e.preventDefault();
});
$(document).on('pjax:beforeSend', '#adverList-pjax', function(xhr, options) {
	showLoading('adverList-pjax', false);
});
$(document).on('pjax:complete', '#adverList-pjax', function(xhr, textStatus, options) {
	hideLoading('adverList-pjax', false);
});
script;
$this->registerJs($js, View::POS_READY);

//loading categories with Ajax
$categoriesListUrl = Url::to(['/categories/categories-list']);
$initCategoriesListScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$categoriesListUrl}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results);});
    }
}
SCRIPT;

$met_description = Yii::t('app',
        'Introduce your business, Your business on the map, image gallery for your business, attachments for your business, Mark your business location in map');
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => $met_description,
]);
$this->registerMetaTag([
    'name' => 'description',
    'content' => $met_description,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $met_description,
]);
?>
<div class="row">
    <div class="col-md-2">
        <?php
        $form = ActiveForm::begin([
                'id' => $searchModel->formName(),
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'method' => 'get',
        ]);
        ?>
        <?=
        $form->field($searchModel, 'category_id')->widget(Select2::classname(),
            [
            'language' => Yii::$app->helper->getTwoCharLanguage(),
            'size' => Select2::MEDIUM,
            'options' => [
            //'style' => 'max-width:350px;',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => $categoriesListUrl,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                ],
                'initSelection' => new JsExpression($initCategoriesListScript),
            ],
        ]);
        ?>
        <?php
        echo $form->field($searchModel, 'country_id')->widget(Select2::className(),
            [
            'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id',
                'name'),
            'language' => Yii::$app->helper->getTwoCharLanguage(),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
            ]
        );

        echo $form->field($searchModel, 'province_id')->widget(DepDrop::classname(),
            [
            'data' => (!$searchModel->country_id) ? [] :
                ArrayHelper::map(Province::find()->where(['country_id' => $searchModel->country_id])->asArray()->all(),
                    'id', 'name'),
            'type' => DepDrop::TYPE_SELECT2,
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true],
                'language' => Yii::$app->helper->getTwoCharLanguage(),
            ],
            'pluginOptions' => [
                'depends' => ['search-country_id'],
                'url' => Url::to(['/province/dep-list']),
                'loadingText' => Yii::t('app', 'Loading...'),
            ]
        ]);

        echo $form->field($searchModel, 'city_id')->widget(DepDrop::classname(),
            [
            'data' => (!$searchModel->province_id) ? [] :
                ArrayHelper::map(City::find()->where(['province_id' => $searchModel->province_id])->asArray()->all(),
                    'id', 'name'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true],
                'language' => Yii::$app->helper->getTwoCharLanguage(),
            ],
            'pluginOptions' => [
                'depends' => ['search-province_id'],
                'url' => Url::to(['/city/dep-list']),
                'loadingText' => Yii::t('app', 'Loading...'),
            ]
        ]);
        ?>
            <?= $form->field($searchModel, 'title')->textInput(); ?>
            <?= $form->field($searchModel, 'address')->textInput(); ?>
            <?= Html::hiddenInput('view', $adverView,
                ['id' => 'hiddenViewMode']); ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'),
                ['class' => 'btn btn-primary']) ?>
        </div>
            <?php ActiveForm::end(); ?>
        <div class="alert alert-info">
            <?= Yii::t('app',
                'Searching just over the showed area in the map'); ?>
        </div>
    </div>
    <div id="gridView" class="col-md-10"<?= ($adverView === 'grid' ? '' : ' style="display:none;"'); ?>>
            <?= getViewButton('grid'); ?>
        <div class="row">
            <?php
            Pjax::begin([
                'id' => 'adverList-pjax',
                'enablePushState' => true,
                'timeout' => '20000'
            ]);
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_index',
                'layout' => "{pager}\n{summary}\n{items}\n{pager}",
                //'layout' => "{sorter}\n{summary}\n{items}\n{pager}"
                'pager' => [
                    'firstPageLabel' => Yii::t('app', 'First'),
                    'lastPageLabel' => Yii::t('app', 'Last'),
                    'nextPageLabel' => Yii::t('app', 'Next'),
                    'prevPageLabel' => Yii::t('app', 'Previous'),
                ],
            ]);
            Pjax::end();
            ?>
        </div>
    </div>
    <div id="mapView" class="col-md-10"<?= ($adverView === 'map' ? '' : ' style="display:none;"'); ?>>
    <?= getViewButton('map'); ?>
        <div id="map" style="height: 80vh;"></div>
        <div id="map_loading_img" class="map_search_ajax_loader"><img src="<?= Yii::$app->getRequest()->getBaseUrl().'/map/map.gif' ?>"/></div>
    </div><?php /*
      <div class="col-md-2" style="height: 80vh; overflow-y: scroll;">
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      <div class="row">
      <div class="col-md-12">
      <img class="img-thumbnail img-responsive" style="width: 100%; height: 120px">
      </div>
      </div>
      </div> */ ?>
</div>