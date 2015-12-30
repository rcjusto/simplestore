<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'registered') ?>

    <?php // echo $form->field($model, 'billing_contact') ?>

    <?php // echo $form->field($model, 'billing_address1') ?>

    <?php // echo $form->field($model, 'billing_address2') ?>

    <?php // echo $form->field($model, 'billing_city') ?>

    <?php // echo $form->field($model, 'billing_state') ?>

    <?php // echo $form->field($model, 'billing_country') ?>

    <?php // echo $form->field($model, 'billing_zip') ?>

    <?php // echo $form->field($model, 'billing_phone') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
