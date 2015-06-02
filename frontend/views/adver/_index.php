<?php

use yii\helpers\Html;
//use yii\helpers\HtmlPurifier;
//use yii\widgets\DetailView;
use yii\helpers\StringHelper;
use common\models\adver\Adver;
use common\models\gallery\Gallery;

/* @var $model common\models\adver\Adver */
?>
<div class="col-sm-6 col-md-3">
	<div class="thumbnail">
		<?php
		$imageIndex = 0;
		$galleryCount = count($model->gallery);
		foreach ($model->gallery as $key => $gallery) {
			$imageUrl = Gallery::getImageUrlFromOutside($gallery['name'], $gallery['adver_id'], 242, 200);
			$imageIndex = $key + 1;
			$galleryStyle = ($galleryCount !== 1 ? 'cursor: pointer;' : '') . ($imageIndex !== 1 ? 'display: none;' : '');
			$imageScript = ($galleryCount !== 1 ? "jQuery('#adversListImage" . $gallery->adver_id . $imageIndex . "').hide();" : '');
			$imageScript .= ($galleryCount === $imageIndex ? "jQuery('#adversListImage" . $gallery->adver_id . "1').show();" :
					"jQuery('#adversListImage" . $gallery->adver_id . ($imageIndex + 1) . "').show();");
			echo '<img class="img-thumbnail img-responsive" id="adversListImage' . $gallery->adver_id . $imageIndex . '" onclick="' . $imageScript . '" alt="' . $gallery->title . '" style="' . $galleryStyle . '" src="' . $imageUrl . '"/>';
		}
		$address = '';
		if (isset($model['country']['name']) && $model['country']['name'] != '')
			$address .= $model['country']['name'] . ', ';
		if (isset($model['province']['name']) && $model['province']['name'] != '')
			$address .= $model['province']['name'] . ', ';
		if (isset($model['city']['name']) && $model['city']['name'] != '')
			$address .= $model['city']['name'] . ', ';
		$address = rtrim($address, ', ');
		?>
		<?php if ($galleryCount > 0): ?>
		<div class="listing-gallery-icon">
		<?= $galleryCount; ?> <img src="<?= Yii::$app->getRequest()->getBaseUrl(); ?>/static_asset/image.png" alt="<?= ($galleryCount . ' ' . Yii::t('app', 'Image'));?>"/>
		</div>
		<?php endif; ?>
		<div class="caption">
			<h3><?php echo Html::encode($model->title) ?></h3>
			<p><?= StringHelper::truncateWords($model->description, 40, '...', false); ?></p>
			<p><span class="label label-success"><?= Html::encode($model['category']['name']); ?></span>
			<span class="label label-success"><?= Html::encode($address); ?></span></p>
			<p><a target="_blank" data-pjax="0" class="btn btn-info" href="<?= Adver::generateLink($model['id'], $model['title'], $model['category']['name'], $model['country']['name'], $model['province']['name'], $model['city']['name'], $model['address'], $model['lang']); ?>"><?= Yii::t('app', 'Detail'); ?></a></p>
		</div>
	</div>
</div>
