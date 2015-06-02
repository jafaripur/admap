<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\widgets\Alert;
use common\models\adver\Adver;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\adver\AdverListSearch */

$this->title = Yii::t('app', 'My advertisement');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
		<div id="adver-message-container"></div>
		<?= Alert::widget(); ?>
        <?php
        Pjax::begin([
			'id' => 'adverList-pjax',
			'enablePushState' => true,
			'timeout' => '20000'
        ]);
        ?>
        <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    //['class' => 'yii\grid\CheckboxColumn'],
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
					[
						'class' => 'yii\grid\DataColumn',
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function ($data){
							return Html::a(Html::encode($data->title),
								Adver::generateLink($data['id'], $data['title'], $data['category']['name'], $data['country']['name'], $data['province']['name'], $data['city']['name'], $data['address'], $data['lang']),
								['data-pjax' => '0', 'target' => '_blank']);
                        }
					],
                    'category.name',
                    'country.name',
                    'province.name',
                    'city.name',
					'lang',
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
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'status',
                        'filter' => $searchModel->getStatusList(),
                        'format' => 'html',
                        'value' => function ($data){
                            return $data->getStatusImage($data->status);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url);
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Yii::$app->helper->createDeleteButton(Url::to(['/adver/delete', 'id' => $model->id]), '', 'adverList-pjax', 'adver-message-container');
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