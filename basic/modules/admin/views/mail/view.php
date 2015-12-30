<?php

use app\models\Mail;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-view">

    <h1>Mail # <?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created',
            'from',
            'to',
            'subject',
            'statusDesc',
        ],
    ]) ?>
    <iframe style="width: 100%;height: 800px;border: 0 none;" src="<?=\yii\helpers\Url::to(['test','id'=>$model->id])?>">
        <?=$model->body?>
    </iframe>
</div>
