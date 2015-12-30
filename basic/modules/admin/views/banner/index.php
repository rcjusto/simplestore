<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Banners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Banner'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'zone',
                'width'=>'310px',
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],

            'zone',
            [
                'width' => '60px',
                'attribute' => 'active',
                'class' => '\kartik\grid\BooleanColumn',
            ],
            ['attribute'=>'position','hAlign'=>GridView::ALIGN_RIGHT],
            'link',

            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update}  {delete}',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]
       ],
    ]); ?>

</div>
