<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\user\UserSearch */

$this->title = Yii::t('app', 'Users Manager');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
			<div id="message_contianer"></div>
        <?php
        Pjax::begin([
			'id' => 'userList-pjax',
			'enablePushState' => true,
			'timeout' => '20000'
        ]);
        ?>
        <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                /*'tableOptions' => [
                    'class' => 'table table-hover table-bordered'
                ],*/
                'columns' => [
                    //['class' => 'yii\grid\CheckboxColumn'],
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'username',
                    'email',
                    /*[
                        'attribute' => 'mmm',
                        'value' => function ($model) {
                            return $model->categoriesCount;
                        } 
                    ],*/
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
							return $data->getStatusButton($data->status, Url::to(['/user/disable', 'id' => $data->id]), 'UserDisable', 'userList-pjax', 'message_contianer');
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Yii::$app->helper->createUpdateButton($url, 'UserEdit', true);
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
								return Yii::$app->helper->createDeleteButton($url, 'UserDelete', 'userList-pjax', 'message_contianer');
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