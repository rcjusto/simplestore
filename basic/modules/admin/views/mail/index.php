<?php

use app\models\Mail;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'created',
            'to',
            'subject',
            [
                'attribute'=>'status',
                'value' => function($data){
                    return $data->getStatusDesc();
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Mail'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
