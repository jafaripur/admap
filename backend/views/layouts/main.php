<?php
use \Yii;
use common\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="<?php echo Yii::$app->getRequest()->getBaseUrl(); ?>/favicon.ico" type="image/x-icon"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue">
    <?php $this->beginBody() ?>
    <?php
        NavBar::begin([
            'brandLabel' => Yii::t('app', 'Administrator'),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-default navbar-fixed-top',
            ],
            'renderInnerContainer' => true,
            'innerContainerOptions' => [
                'class' => "container-fluid",
            ]
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => backend\components\MenuHelper::getAdminTopMenuItem(),
        ]);
        NavBar::end();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </div>
        </div>
        <?= $content ?>
    </div>
	<footer class="footer">
		<div style="text-align: center;">
			<p><strong><?= Yii::t('app', 'Version {version}', ['version' => Yii::$app->version]); ?></strong></p>
			<hr>
			<p><a href="http://yiiframework.com" target="_blank"><img src="<?php echo Yii::$app->getRequest()->getBaseUrl(); ?>/static_asset/yii.png" title="Yii"></a></p>
		</div>
	</footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
