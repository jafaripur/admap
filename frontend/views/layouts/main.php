<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\assets\AppAsset;

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
	<meta name="robots" content="index, follow">
	<meta name="author" content="Jafaripur">
	<meta name="rating" content="general">
    <?= Html::csrfMetaTags() ?>
	<link rel="canonical" href="<?php echo urldecode(Url::canonical()); ?>">
	<meta property="og:url" content="<?php echo urldecode(Url::canonical()); ?>" />
	<meta property="og:title" content="<?= Html::encode($this->title) ?>" />
	<meta property="fb:app_id" content="348428045334410" />
	<meta property="article:author" content="https://www.facebook.com/jafaripur" />
	<?php //<meta property="article:publisher" content="https://www.facebook.com/cnn" /> ?>
	<link rel='alternate' type='application/rss+xml' title='RSS' href='<?= Url::to(['/feed/rss'], true);?>'>
	<link rel='alternate' type='application/atom+xml' title='Atom' href='<?= Url::to(['/feed/atom'], true);?>'>
	<link rel="sitemap" href="<?= Url::to(['/feed/sitemap'], true);?>">
	<link rel="icon" href="<?php echo Yii::$app->getRequest()->getBaseUrl(); ?>/favicon.ico" type="image/x-icon"/>
    <title><?= Html::encode($this->title) ?></title>
	<meta name="msvalidate.01" content="A52103C3AFA86C036E6C24B30F40AC46" />
    <?php $this->head() ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-59448117-1', 'auto');
		ga('send', 'pageview');
	</script>
    <?php $this->beginBody() ?>
	<header class="header">
	<?php
		NavBar::begin([
			'brandLabel' => Yii::t('app', 'Introduce business') . '(Beta)',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar-default navbar-fixed-top',
			],
			'renderInnerContainer' => true,
			'innerContainerOptions' => [
				'class' => "container-fluid",
			]
		]);

		echo Nav::widget([
			'options' => ['class' => 'navbar-nav'],
			'items' => frontend\components\MenuHelper::getFrontendTopMenuItem(),
		]);
		NavBar::end();
	?>
	</header>
	<main role="main">
		<div class="container-fluid">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= $content ?>
		</div>
	</main>
	<footer class="footer">
		<div style="text-align: center;">
			<strong><?= Yii::t('app', 'Version {version}', ['version' => Yii::$app->version]); ?></strong>
		</div>
	</footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>