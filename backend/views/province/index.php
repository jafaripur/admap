<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\province\ProvinceSearch */

$this->title = Yii::t('app', 'Provinces');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if (Yii::$app->getUser()->can('ProvinceAdd')): ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>'. Yii::t('app', 'New'), ['/province/add'], [
            'class' => 'btn btn-app'
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
            'id' => 'provinceList-pjax',
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
                    'country.name',
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
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, 'ProvinceEdit', false);
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
								return Yii::$app->helper->createDeleteButton($url, 'ProvinceDelete', 'provinceList-pjax', 'message_contianer');
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