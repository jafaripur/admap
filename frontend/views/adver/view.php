<?php

use yii\helpers\Html;
use common\models\gallery\Gallery;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model Array */

$this->title = Html::encode($model['title']);
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::$app->helper->getGoogleMapUrl(), [
    'position' => View::POS_HEAD,
]);

$js = "
$('.fancyGallery').fancybox({
	openEffect	: 'none',
	closeEffect	: 'none'
});
";
$this->registerJs($js);

$js = "
function initializeMap() {
    var defaultLatlng = new google.maps.LatLng(".Html::encode($model['latitude']).", ".Html::encode($model['longitude']).");
    var mapOptions = {
        zoom: 17,
        center: defaultLatlng,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
    };
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    new google.maps.Marker({
        position: defaultLatlng,
        map: map,
    });
}
";
$this->registerJs($js, View::POS_HEAD);
$address = '';
if ($model['country']['name'] != '')
	$address .= $model['country']['name'] . ', ';
if ($model['province']['name'] != '')
	$address .= $model['province']['name'] . ', ';
if ($model['city']['name'] != '')
	$address .= $model['city']['name'] . ', ';
if ($model['address'] != '')
	$address .= $model['address'] . ', ';
$address = Html::encode(rtrim($address, ', '));
$model['category']['name'] = Html::encode($model['category']['name']);
$description = $this->title .', ' . $address . ', ' . $model['category']['name'];
$this->registerMetaTag([
	'property' => 'og:description',
	'content' => $description,
]);
$this->registerMetaTag([
	'name' => 'description',
	'content' => $description,
]);
$this->registerMetaTag([
	'name' => 'keywords',
	'content' => $description,
]);
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=348428045334410&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<div class="row">
	<div class="col-md-7">
		<section>
			<article>
				<header>
					<h1><?= Html::encode($model['title']); ?></h1>
					<div class="row">
						<div class="col-md-12">
							<dl class="dl-horizontal">
								<dt><?= Yii::t('app', 'Category'); ?></dt>
								<dd><?= $model['category']['name']; ?></dd>
								<dt><?= Yii::t('app', 'Address'); ?></dt>
								<dd><?= $address; ?></dd>
							</dl>
						</div>
					</div>
				</header>
				<div class="row">
					<div class="col-md-12">
						<?= $model['description']; ?>
					</div>
				</div>
				<footer>
					<div class="row">
						<div class="col-md-2">
							<div class="fb-share-button" data-href="<?= Yii::$app->getRequest()->getAbsoluteUrl(); ?>" data-layout="button_count"></div>
						</div>
						<div class="col-md-2">
							<div class="g-plus" data-action="share" data-annotation="bubble"></div>
						</div>
					</div>
				</footer>
			</article>
		</section>
	</div>
	<div class="col-md-5">
		<div role="tabpanel" class="">
			<ul class="nav nav-tabs" role="tablist">
				<?php if (!empty($model['gallery'])): ?>
				<li role="presentation" class="active"><a href="#gallery" aria-controls="gallery" role="tab" data-toggle="tab"><?= Yii::t('app', 'Gallery'); ?></a></li>
				<?php endif; ?>
				<?php if (!empty($model['attachment'])): ?>
				<li role="presentation"><a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab"><?= Yii::t('app', 'Attachments'); ?></a></li>
				<?php endif; ?>
				<li role="presentation"><a href="#mapPanel" onclick="setTimeout(function(){initializeMap();}, 500);" aria-controls="mapPanel" role="tab" data-toggle="tab"><?= Yii::t('app', 'Map'); ?></a></li>
			</ul>
			<div class="tab-content">
				<?php if (!empty($model['gallery'])): ?>
				<div role="tabpanel" class="tab-pane fade in active" id="gallery">
					<?php foreach($model['gallery'] as $gallery): ?>
					<?php
					$this->registerMetaTag(['name' => 'og:image', 'content' => Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id'], 0, 0, 70, true)])
					?>
					<a class="fancyGallery" rel="gallery<?=$gallery['adver_id']; ?>" href="<?= Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id']); ?>" title="<?= Html::encode($gallery['title']); ?>">
						<img class="img-thumbnail img-responsive" src="<?= Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id'], 160, 105) ?>" alt="<?= Html::encode($gallery['title']); ?>" />
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if (!empty($model['attachment'])): ?>
				<div role="tabpanel" class="tab-pane fade" id="attachments">
					<ul style="margin-top: 30px;">
						<?php foreach($model['attachment'] as $attachment): ?>
						<li><a href="<?= Url::to(['/adver/download-attachment', 'id' => $attachment['id']]) ?>" target="_blank" title="<?= Html::encode($attachment['title']); ?>"><?= Html::encode($attachment['title']); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<div role="tabpanel" class="tab-pane fade" id="mapPanel">
					<div id="map" style="height: 28em;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6" id="disqus_thread"></div>
</div>
<script type="text/javascript">
var disqus_shortname = 'admap';
var disqus_identifier = '<?= $model['id']; ?>';
var disqus_category_id = '<?= $model['category']['id']; ?>';
var disqus_title = '<?= $this->title; ?>';
var disqus_url = '<?= Yii::$app->getRequest()->getAbsoluteUrl(); ?>';
(function() {
	var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>