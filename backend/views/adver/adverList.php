<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\adver\AdverListSearch */

$this->title = Yii::t('app', 'Advertisement');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
		<div id="adver-message-container"></div>
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
                    'title',
					'user.username',
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
                        'format' => 'raw',
                        'value' => function ($data){
                            return $data->getStatusButton($data->status, Url::to(['/adver/disable', 'id' => $data->id]), 'AdverDisable', 'adverList-pjax', 'adver-message-container');
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, 'AdverUpdate');
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Yii::$app->helper->createDeleteButton(Url::to(['/adver/delete', 'id' => $model->id]), 'AdverDelete', 'adverList-pjax', 'adver-message-container');                            }
                        ],
                    ],
                ],
            ]);
        ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>