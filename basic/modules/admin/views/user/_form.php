<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered')->textInput() ?>

    <?= $form->field($model, 'billing_contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_address1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_address2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
