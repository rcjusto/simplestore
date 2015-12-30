<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

$message_error =  Yii::$app->session->getFlash('products_error');
$message_info =  Yii::$app->session->getFlash('products_info');

?>
<style>
    table.table td img {max-height: 24px;}
</style>
<div class="product-index">

    <div class="row">
        <div class="col-xs-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-6" style="text-align: right;margin-top: 25px;">
            <?= Html::a(Yii::t('app', 'Create Product'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= !empty($message_error) ? Html::tag('div', $message_error, ['class'=>'alert alert-danger']) : '' ?>
    <?= !empty($message_info) ? Html::tag('div', $message_info, ['class'=>'alert alert-success']) : '' ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            [
                'width' => '60px',
                'attribute' => 'active',
                'class' => '\kartik\grid\BooleanColumn',
            ],
            [
                'width' => '60px',
                'hAlign'=>GridView::ALIGN_CENTER,
                'label' => 'Img',
                'format' => 'raw',
                'value' => function ($data) {
                    $list = $data->getListImages();
                    return count($list)>0 ? Html::img($list[0]) : '';
                }
            ],
            'code',
            [
                'attribute' => 'name',
                'label' => 'Name',
                'value' => function ($data) {
                    return $data->getName();
                }
            ],
            [
                'attribute' => 'category',
                'label' => 'Category',
                'value' => function ($data) {
                    return $data->category->getName();
                }
            ],
            [
                'attribute' => 'provider',
                'label' => 'Provider',
                'value' => function ($data) {
                    return $data->provider->name;
                }
            ],
            ['attribute'=>'stock','hAlign'=>GridView::ALIGN_RIGHT],
            ['attribute'=>'price_custom','hAlign'=>GridView::ALIGN_RIGHT],
            ['attribute'=>'featured','hAlign'=>GridView::ALIGN_CENTER],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update}  {delete}',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]
        ],
        'responsive'=>true,
        'hover'=>true,
        'resizableColumns'=>true,
        'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
        'toolbar'=>[
            '{export}',
            '{toggleData}'
        ],
    ]); ?>

</div>
