<?php

use app\models\Nicaragua;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterForm */
/* @var $form ActiveForm */
?>
<div class="site-register">

    <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>
    <?= Html::activeHiddenInput($model, 'shipping_country') ?>

    <div class="row form">
        <div class="col-lg-6">
            <h3>Datos de su destinatario en Nicaragua</h3>
            <?= $form->field($model, 'shipping_contact') ?>
            <?= $form->field($model, 'shipping_address1') ?>
            <?= $form->field($model, 'shipping_address2') ?>
            <?= $form->field($model, 'shipping_city') ?>
            <?= $form->field($model, 'shipping_state')->dropDownList(Nicaragua::getStates()) ?>
            <?= $form->field($model, 'shipping_zip') ?>
            <div class="form-group field-registerform-shipping_country">
                <label class="control-label col-sm-3" for="registerform-shipping_country"><?= Yii::t('app','Country')?></label>
                <div class="col-sm-6">
                    <input class="form-control" readonly type="text" value="Nicaragua">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <?= $form->field($model, 'shipping_email') ?>
            <?= $form->field($model, 'shipping_phone') ?>
            <?= $form->field($model, 'shipping_contact2') ?>
        </div>

        <div class="col-lg-6">
            <h3>Datos para identificarse en el sitio</h3>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password') ?>
            <h3>Sus datos personales</h3>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'billing_address1') ?>
            <?= $form->field($model, 'billing_address2') ?>
            <?= $form->field($model, 'billing_city') ?>
            <?= $form->field($model, 'billing_state') ?>
            <?= $form->field($model, 'billing_zip') ?>
            <?= $form->field($model, 'billing_country')->dropDownList(Nicaragua::$countries) ?>
            <?= $form->field($model, 'billing_phone') ?>
        </div>

    </div>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-register -->
