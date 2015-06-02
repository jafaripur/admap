<?php

use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = Yii::t('app', "Administrator");

$js = <<<SCRIPT
function clearCacheAndAssets(url, container){
	showLoading(container, false);
	$.post(url, null, function(){}).always(function(){
		hideLoading(container, false);
	});
}
SCRIPT;
$this->registerJs($js, View::POS_HEAD);
?>
<?php /*<a class="btn btn-app">
	<span class="badge bg-yellow">3</span>
	<i class="glyphicon glyphicon-cd"></i>
	test
</a>*/?>
<div class="container">
	<div class="row well well-sm">
		<div class="col-md-12" id="maintenance">
			<legend><?= Yii::t('app', 'Maintenance'); ?></legend>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<div class="btn-group" role="group">
					<button onclick="clearCacheAndAssets('<?= Url::to(['/admin/clear-cache']); ?>', 'maintenance');" type="button" class="btn btn-success" role="button"><?= Yii::t('app', 'Clear system cache'); ?></button>
				</div>
				<div class="btn-group" role="group">
					<button onclick="clearCacheAndAssets('<?= Url::to(['/admin/clear-assets']); ?>', 'maintenance');" type="button" class="btn btn-success" role="button"><?= Yii::t('app', 'Clear website assets'); ?></button>
				</div>
				<div class="btn-group" role="group">
					<button onclick="clearCacheAndAssets('<?= Url::to(['/admin/clear-thumbnails']); ?>', 'maintenance');" type="button" class="btn btn-success" role="button"><?= Yii::t('app', 'Clear thumbnails images'); ?></button>
				</div>
			</div>
		</div>	
	</div>
</div>