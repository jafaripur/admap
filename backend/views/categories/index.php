<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\categories\CategoriesSearch */

$this->title = Yii::t('app', 'Categories Manager');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if (Yii::$app->getUser()->can('CategoryAdd')): ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>'. Yii::t('app', 'New'), ['/categories/add'], [
            'class' => 'btn btn-app',
			'title' => Yii::t('app', 'Add')
        ]) ?>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
			<div id="message_contianer"></div>
        <?php
        Pjax::begin([
			'id' => 'categoriesList-pjax',
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
                    'user.username',
                    'name',
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
							return $data->getStatusButton($data->status, Url::to(['/categories/disable', 'id' => $data->id]), 'CategoryDisable', 'categoriesList-pjax', 'message_contianer');
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, 'CategoryEdit');
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
								return Yii::$app->helper->createDeleteButton($url, 'CategoryDelete', 'categoriesList-pjax', 'message_contianer');
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