<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProviderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Providers');
$this->params['breadcrumbs'][] = $this->title;

$message_error =  Yii::$app->session->getFlash('providers_error');
$message_info =  Yii::$app->session->getFlash('providers_info');

?>
<div class="provider-index">

    <div class="row">
        <div class="col-xs-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-6" style="text-align: right;margin-top: 25px;">
            <?= Html::a(Yii::t('app', 'Create Provider'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= !empty($message_error) ? Html::tag('div', $message_error, ['class'=>'alert alert-danger']) : '' ?>
    <?= !empty($message_info) ? Html::tag('div', $message_info, ['class'=>'alert alert-success']) : '' ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [

            'id',
            'name',
            'email',

            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update}  {delete}',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]
        ],
    ]); ?>

</div>
