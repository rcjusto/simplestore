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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'created',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {return $data->getStatusDesc(true, true);},
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
                'attribute' => 'total'
            ],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{view}',
            ]
        ],
    ]); ?>

</div>
