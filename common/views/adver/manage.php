<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\country\Country;
use common\models\province\Province;
use common\models\city\City;
use yii\web\View;
use kartik\widgets\DepDrop;
use vova07\imperavi\Widget;
use common\widgets\Alert;
use dosamigos\fileupload\FileUpload;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $adverModel \common\models\adver\Adver */

/* @var $gallery \common\models\gallery\Gallery */ //just in editing by owner
/* @var $galleryDataProvider yii\data\ActiveDataProvider */
/* @var $gallerySearchModel common\models\gallery\GallerySearch */

/* @var $attachment \common\models\attachment\Attachment */ //just in editing by owner
/* @var $attachmentDataProvider yii\data\ActiveDataProvider */
/* @var $attachmentSearchModel common\models\attachment\AttachmentSearch */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'My advertisement'),
    'url' => ['/adver/adver-list']
];
if ($adverModel->isNewRecord){
    $this->title = Yii::t('app', 'Register Advertisement');
    $this->params['breadcrumbs'][] = $this->title;
}
else{
    $this->title = Yii::t('app', 'Update advertisement {advertisement}', [
       'advertisement' => Html::encode($adverModel->title)
    ]);
    $this->params['breadcrumbs'][] = Html::encode($adverModel->title);
}
/*
$js ="
$('form#{$adverModel->formName()}').on('beforeSubmit', function(e, \$form) {
    return ajaxLoadHtml('{$adverModel->formName()}', 'register-container');
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);
*/

$this->registerJsFile(Yii::$app->helper->getGoogleMapUrl(), [
    'position' => View::POS_HEAD,
]);
$js ="
var map;
var marker;
function initialize() {
    var defaultLatlng = new google.maps.LatLng(".implode(',', $adverModel->getLatitudeLongitude()).");
    var mapOptions = {
        zoom: 11,
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
        $('#adver-latitude').val(lat);
        $('#adver-longitude').val(lng);
        var newDefaultLatlng = new google.maps.LatLng(lat, lng);
        marker.setPosition(newDefaultLatlng);
    });
    google.maps.event.addListener(marker, 'drag', function(event) {
        $('#adver-latitude').val(event.latLng.lat());
        $('#adver-longitude').val(event.latLng.lng());
    });
}
function changeMarkerPosition()
{
    var new_marker_position = new google.maps.LatLng($('#adver-latitude').val(), $('#adver-longitude').val());
    marker.setPosition(new_marker_position);
    map.setCenter(new_marker_position);
}
google.maps.event.addDomListener(window, 'load', initialize);
";
$this->registerJs($js, View::POS_HEAD);


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
// for add image to dropdown
$js = <<< SCRIPT
function getLocation(){
    var fullAddress = '';
    var country = $('#adver-country_id option:selected').text();
    var province = $('#adver-province_id option:selected').text();
    var city = $('#adver-city_id option:selected').text();
    var address = $('#adver-address').val();
    if (country !== '')
        fullAddress += country + ', ';
    if (province !== '')
        fullAddress += province + ', ';
    if (city !== '')
        fullAddress += city + ', ';
    if (address !== '')
        fullAddress += address;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': fullAddress}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK)
        {
            map.setCenter(results[0].geometry.location, 14);
            marker.setPosition(results[0].geometry.location);
            map.fitBounds(results[0].geometry.viewport);
            $('#adver-latitude').val(results[0].geometry.location.lat());
            $('#adver-longitude').val(results[0].geometry.location.lng());
        }
    });
}
SCRIPT;
$this->registerJs($js, View::POS_HEAD);

$userListUrl = Url::to(['/user/user-list']);
$initUserScript = <<< SCRIPT
function (element, callback) {
    var id=\$(element).val();
    if (id !== "") {
        \$.ajax("{$userListUrl}?id=" + id, {
            dataType: "json"
        }).done(function(data) { callback(data.results);});
    }
}
SCRIPT;
?>
<div id="register-container">
    <?php
    echo Alert::widget();
    
    $form = ActiveForm::begin([
        'id' => $adverModel->formName(),
        'enableClientValidation' => true,
    ]);
    if (!$adverModel->isNewRecord){
        echo Html::activeHiddenInput($adverModel, 'id');
    }
	echo $form->errorSummary($adverModel);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#geographic" aria-controls="geographic" role="tab" data-toggle="tab"><?= Yii::t('app', 'Geographic location') ?></a></li>
                  <li role="presentation"><a href="#information" aria-controls="information" role="tab" data-toggle="tab"><?= Yii::t('app', 'Information') ?></a></li>
                </ul>
                <div class="tab-content" style="margin-top: 1em;">
                    <div role="tabpanel" class="tab-pane fade in active" id="geographic">
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                echo $form->field($adverModel, 'country_id')->widget(Select2::className(),
                                        [
                                            'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
                                            'language' => Yii::$app->helper->getTwoCharLanguage(),
                                            'options' => [
                                                'placeholder' => Yii::t('app', 'Select...'),
                                            ],
                                        ]
                                    );

                                echo $form->field($adverModel, 'province_id')->widget(DepDrop::classname(), [
                                        'data'=> ($adverModel->isNewRecord) ? [] :
                                            ArrayHelper::map(Province::find()->where(['country_id' => $adverModel->country_id])->asArray()->all(), 'id', 'name'),
                                        'type' => DepDrop::TYPE_SELECT2,
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Select...'),
                                        ],
                                        'select2Options'=>[
                                            'pluginOptions' => ['allowClear' => true ],
                                            'language' => Yii::$app->helper->getTwoCharLanguage(),
                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>['adver-country_id'],
                                            'url' => Url::to(['/province/dep-list']),
                                            'loadingText' => Yii::t('app', 'Loading...'),
                                        ]
                                    ]);

                                echo $form->field($adverModel, 'city_id')->widget(DepDrop::classname(), [
                                        'data'=> ($adverModel->isNewRecord) ? [] :
                                            ArrayHelper::map(City::find()->where(['province_id' => $adverModel->province_id])->asArray()->all(), 'id', 'name'),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Select...'),
                                        ],
                                        'type' => DepDrop::TYPE_SELECT2,
                                        'select2Options'=>[
                                            'pluginOptions' => ['allowClear' => true ],
                                            'language' => Yii::$app->helper->getTwoCharLanguage(),
                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>['adver-province_id'],
                                            'url' => Url::to(['/city/dep-list']),
                                            'loadingText' => Yii::t('app', 'Loading...'),
                                        ]
                                    ]);

                                echo $form->field($adverModel, 'address')->textInput();
                                echo $form->field($adverModel, 'latitude')->textInput([
                                    'onchange' => 'changeMarkerPosition();',
                                ])->hint(Yii::t('app', 'Select from map'));
                                echo $form->field($adverModel, 'longitude')->textInput([
                                    'onchange' => 'changeMarkerPosition();',
                                ])->hint(Yii::t('app', 'Select from map'));
                                ?>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="map" style="height: 28em;"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info btn-lg center-block" onclick="getLocation();"><?= Yii::t('app', 'Get location') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="information">
                        <div class="row">
                            <div class="col-md-2">
                            <?= $form->field($adverModel, 'title')->textInput() ?>
                            <?= $form->field($adverModel, 'category_id')->widget(Select2::classname(), [
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
                                            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                        ],
                                        'initSelection' => new JsExpression($initCategoriesListScript),
                                    ],
                                ]);
								echo $form->field($adverModel, 'lang')->dropDownList(Yii::$app->helper->getLanguageForMultiLingual());
                                if (!$adverModel->isNewRecord && Yii::$app->getUser()->can('AdverUpdate')){
                                    echo $form->field($adverModel, 'user_id')->widget(Select2::classname(), [
                                        'language' => Yii::$app->helper->getTwoCharLanguage(),
                                        'pluginOptions' => [
                                            'minimumInputLength' => 2,
                                            'ajax' => [
                                                'url' => $userListUrl,
                                                'dataType' => 'json',
                                                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                            ],
                                            'initSelection' => new JsExpression($initUserScript)
                                        ],
                                    ]);
									echo $form->field($adverModel, 'status')->dropDownList($adverModel->getStatusList());
                                }
                            ?>
                            </div>
                            <div class="col-md-5">
                                <?=
                                $form->field($adverModel, 'description')->widget(Widget::className(), [
                                    'settings' => [
                                        'lang' => Yii::$app->helper->getTwoCharLanguage(),
                                        'minHeight' => 300,
                                        'maxHeight' => 300,
                                        'direction' => Yii::$app->helper->isRtl() ? 'rtl' : 'ltr',
                                        'convertLinksUrl' => true,
                                        'plugins' => [
                                            'fullscreen',
                                            'textdirection',
                                            'table',
                                            'fontsize',
                                            'fontcolor',
                                        ]
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
			<?= Html::submitButton(Yii::t('app', 'Save'), ['style' => 'margin-top:1em;', 'class' => 'btn btn-success btn-lg']) ?>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
    <div class="row">
        <div class="col-md-12">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#gallery" aria-controls="gallery" role="tab" data-toggle="tab"><?= Yii::t('app', 'Gallery') ?></a></li>
                  <li role="presentation"><a href="#attachment" aria-controls="attachment" role="tab" data-toggle="tab"><?= Yii::t('app', 'Attachment') ?></a></li>
                </ul>
                <div class="tab-content" style="margin-top: 1em;">
                    <div role="tabpanel" class="tab-pane fade in active" id="gallery">
						<?php if ($adverModel->isNewRecord): ?>
						<div class="alert alert-warning"><?= Yii::t('app', 'After saving advertisement, You can add gallery for your advertisement'); ?></div>
						<?php else: ?>
						<?php if (isset($gallery)): ?>
						<div class="row">
							<div class="col-md-4">
								<div id="gallery_message"></div>
							<?php
								$galleryForm = ActiveForm::begin([
									'id' => $gallery->formName(),
								]);
							?>
							<?= $galleryForm->field($gallery, 'title')->textInput(); ?>
							<?= FileUpload::widget([
								'model' => $gallery,
								'attribute' => 'image',
								'url' => ['adver/gallery-add', 'id' => $adverModel->id],
								'clientOptions' => [
									'dataType' => 'json',
									'autoUpload' => false,
									'maxFileSize' => Yii::$app->params['maxGalleryImageSize']
								],
								// ... 
								'clientEvents' => [
									'fileuploaddone' => 'function(e, data) {
															$("#gallery_message").html(data.result.message);
															if (!data.result.error){
																reloadPjax("gallery-pjax");
															}
															$("#gallery-upload-container").html("");
														}',
									'fileuploadfail' => 'function(e, data) {
															$("#gallery_message").addClass("alert alert-danger").html("'.Yii::t('app', 'Error!').'");
														}',

									'fileuploadsubmit' => 'function(e, data) {
															$("#gallery_message").removeClass().html("");
															data.formData = {"Gallery[title]": $("#gallery-title").val()};
														}',
									'fileuploadadd' => "function(e, data) {
															$('#gallery-upload-container').html('');
															 data.context = $('<button/>').text('".Yii::t('app', 'Upload')."').addClass('btn btn-primary')
															.appendTo('#gallery-upload-container')
															.click(function () {
																data.context = $('<p/>').text('".Yii::t('app', 'Uploading...')."').replaceAll($(this));
																data.submit();
															});
														}",
								],
							]);
							?>
							<?php ActiveForm::end(); ?>
							<div id="gallery-upload-container" class="form-group"></div>
							</div>
						</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<div class="table table-responsive">
								<?php
								Pjax::begin([
									'id' => 'gallery-pjax',
									'enablePushState' => true,
									'timeout' => '20000'
								]);
								?>
								<?=
									GridView::widget([
										'dataProvider' => $galleryDataProvider,
										'filterModel' => $gallerySearchModel,
										'columns' => [
											//['class' => 'yii\grid\CheckboxColumn'],
											['class' => 'yii\grid\SerialColumn'],
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'image',
												'format' => 'html',
												'value' => function ($data){
													return Html::img($data->getImageUrl(160, 108), [
														'class' => 'img-thumbnail img-responsive',
													]);
												}
											],
											'title',
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'created_at',
												'value' => function ($data){
													return Yii::$app->dateTimeAction->timeToDate('l j F Y H:i', $data->created_at);
												}
											],
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'updated_at',
												'value' => function ($data){
													return Yii::$app->dateTimeAction->timeToDate('l j F Y H:i', $data->updated_at);
												}
											],
											[
												'class' => 'yii\grid\ActionColumn',
												'template'=>'{delete}',
												'buttons' => [
													'delete' => function ($url, $model, $key) {
														return Yii::$app->helper->createDeleteButton(Url::to(['/adver/gallery-delete', 'id' => $model->id]), '', 'gallery-pjax', 'gallery_message');
													}
												],
											],
										],
									]);
								?>
								<?php Pjax::end(); ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="attachment">
						<?php if ($adverModel->isNewRecord): ?>
						<div class="alert alert-warning"><?= Yii::t('app', 'After saving advertisement, You can add attachment for your advertisement'); ?></div>
						<?php else: ?>
						<?php if (isset($attachment)): ?>
						<div class="row">
							<div class="col-md-4">
								<div id="gallery_message"></div>
							<?php
								$attachmentForm = ActiveForm::begin([
								'id' => $attachment->formName(),
							]);
							?>
							<?= $attachmentForm->field($attachment, 'title')->textInput(); ?>
							<?= FileUpload::widget([
								'model' => $attachment,
								'attribute' => 'attachment',
								'url' => ['adver/attachment-add', 'id' => $adverModel->id],
								'clientOptions' => [
									'dataType' => 'json',
									'autoUpload' => false,
									'maxFileSize' => Yii::$app->params['maxAttachmentSize']
								],
								// ... 
								'clientEvents' => [
									'fileuploaddone' => 'function(e, data) {
															$("#attachment_message").html(data.result.message);
															if (!data.result.error){
																reloadPjax("attachment-pjax");
															}
															$("#attachment-upload-container").html("");
														}',
									'fileuploadfail' => 'function(e, data) {
															$("#attachment_message").addClass("alert alert-danger").html("'.Yii::t('app', 'Error!').'");
														}',

									'fileuploadsubmit' => 'function(e, data) {
															$("#attachment_message").removeClass().html("");
															data.formData = {"Attachment[title]": $("#attachment-title").val()};
														}',
									'fileuploadadd' => "function(e, data) {
															$('#attachment-upload-container').html('');
															 data.context = $('<button/>').text('".Yii::t('app', 'Upload')."').addClass('btn btn-primary')
															.appendTo('#attachment-upload-container')
															.click(function () {
																data.context = $('<p/>').text('".Yii::t('app', 'Uploading...')."').replaceAll($(this));
																data.submit();
															});
														}",
								],
							]);
							?>
							<?php ActiveForm::end(); ?>
							<div id="attachment-upload-container" class="form-group"></div>
							</div>
						</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<div class="table table-responsive">
									<div id="attachment_message"></div>
								<?php
								Pjax::begin([
									'id' => 'attachment-pjax',
									'enablePushState' => true,
									'timeout' => '20000'
								]);
								?>
								<?=
									GridView::widget([
										
										'dataProvider' => $attachmentDataProvider,
										'filterModel' => $attachmentSearchModel,
										'columns' => [
											//['class' => 'yii\grid\CheckboxColumn'],
											['class' => 'yii\grid\SerialColumn'],
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'attachment',
												'format' => 'raw',
												'value' => function ($data){
													return Html::a(Yii::t('app', 'Download'), Url::to(['/adver/download-attachment', 'id' => $data->id]), [
														'target' => "_blank",
														'data-pjax' => '0',
													]);
												}
											],
											'title',
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'created_at',
												'value' => function ($data){
													return Yii::$app->dateTimeAction->timeToDate('l j F Y H:i', $data->created_at);
												}
											],
											[
												'class' => 'yii\grid\DataColumn',
												'attribute' => 'updated_at',
												'value' => function ($data){
													return Yii::$app->dateTimeAction->timeToDate('l j F Y H:i', $data->updated_at);
												}
											],
											[
												'class' => 'yii\grid\ActionColumn',
												'template'=>'{delete}',
												'buttons' => [
													'delete' => function ($url, $model, $key) {
														return Yii::$app->helper->createDeleteButton(Url::to(['/adver/attachment-delete', 'id' => $model->id]), '', 'attachment-pjax', 'attachment_message');
													}
												],
											],
										],
									]);
								?>
								<?php Pjax::end(); ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>