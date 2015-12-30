<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Blocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Block'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'width' => '60px',
                'attribute' => 'active',
                'class' => '\kartik\grid\BooleanColumn',
            ],
            [
                'attribute' => 'title',
                'label' => 'Title',
                'value' => function ($data) {
                    return $data->getTitle();
                }
            ],
            'url_code',
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update}  {delete}',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]
        ],
    ]); ?>

</div>
