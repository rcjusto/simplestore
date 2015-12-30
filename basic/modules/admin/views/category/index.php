<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

$message_error =  Yii::$app->session->getFlash('categories_error');
$message_info =  Yii::$app->session->getFlash('categories_info');
?>
<div class="category-index">

    <div class="row">
        <div class="col-xs-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-6" style="text-align: right;margin-top: 25px;">
            <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= !empty($message_error) ? Html::tag('div', $message_error, ['class'=>'alert alert-danger']) : '' ?>
    <?= !empty($message_info) ? Html::tag('div', $message_info, ['class'=>'alert alert-success']) : '' ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [

            'id',
            'url_code:url',
            'parentName',
            'name',

            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update}  {delete}',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]
        ],
    ]); ?>

</div>
