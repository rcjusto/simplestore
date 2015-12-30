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

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'billing_address1') ?>

    <?= $form->field($model, 'billing_address2') ?>

    <?php // echo $form->field($model, 'billing_city') ?>

    <?php // echo $form->field($model, 'billing_state') ?>

    <?php // echo $form->field($model, 'billing_country') ?>

    <?php // echo $form->field($model, 'billing_zip') ?>

    <?php // echo $form->field($model, 'billing_phone') ?>

    <?php // echo $form->field($model, 'billing_contact') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
