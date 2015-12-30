<?php

use app\models\Order;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'created',
            [
                'attribute' => 'status',
                'value' => function($data) {return Order::getStatuses($data->status);},
            ],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app','User'),
                'value' => function($data) {return $data->user->fullname;},
            ],
            [
                'label' => Yii::t('app','Destination'),
                'value' => function($data) {return !is_null($data->orderShipping) ? $data->orderShipping->shipping_contact : '';},
            ],
            [
                'label' => Yii::t('app','Pending'),
                'value' => function($data) {
                    $pending = $data->getProviderPending(Yii::$app->controller->getProvider()->id);
                    return ($pending>0) ? Yii::t('app','{it} item(s)',['it'=>$pending]) : Yii::t('app', 'Complete');
                },
            ],
            [
                'label' => Yii::t('app','Cost'),
                'value' => function($data) {return $data->getProviderCost(Yii::$app->controller->getProvider()->id);},
            ],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{view}',
            ]
        ],
    ]); ?>

</div>
