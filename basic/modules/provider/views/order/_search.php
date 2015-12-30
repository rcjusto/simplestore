<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'buyer') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'destination') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'created_ini') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'created_end') ?>
        </div>
        <div class="col-sm-2">
            <label style="display: block">&nbsp;</label>
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default', 'onclick'=>"$(this).closest('form').find('input,select').each(function(){ $(this).val('') });"]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
