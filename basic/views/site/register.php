<?php

use app\models\Nicaragua;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterForm */
/* @var $form ActiveForm */
/* @var $back string */

$storeName = \app\models\Property::getPropertyValue('store_name','RecibiloAlla');

?>
<div class="site-register">

    <h2><?=Yii::t('app','Register Your Information')?></h2>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'formRegister',
        'options' => [
            'onSubmit'=>"if ($('#cbAgree').is(':checked')) { $('#cbAgreeMsg').hide();return true; } else { $('#cbAgreeMsg').show();return false; }"
        ],
    ]); ?>
    <?= Html::activeHiddenInput($model, 'shipping_country') ?>
    <?= Html::hiddenInput('back', $back) ?>

    <div class="row form">
        <div class="col-lg-6">
            <h3><?= Yii::t('app', 'Recipient/Addressee in Nicaragua') ?></h3>
            <p><?= Yii::t('app', 'Enter your recipient\'s information') ?></p>
            <?= $form->field($model, 'shipping_contact') ?>
            <?= $form->field($model, 'shipping_email') ?>
            <?= $form->field($model, 'shipping_phone') ?>
        </div>

        <div class="col-lg-6">
            <h3><?= Yii::t('app', 'Your Credentials') ?></h3>
            <p><?= Yii::t('app', 'Please enter your e-mail and create a password in order to make purchases at {store}',['store'=>$storeName]) ?></p>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <h3><?= Yii::t('app', 'Personal Information') ?></h3>
            <p><?= Yii::t('app', 'Enter your information to register in {store}',['store'=>$storeName]) ?></p>
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'billing_address1') ?>
            <?= $form->field($model, 'billing_city') ?>
            <?= $form->field($model, 'billing_state') ?>
            <?= $form->field($model, 'billing_zip') ?>
            <?= $form->field($model, 'billing_country')->dropDownList(Nicaragua::$countries) ?>
            <?= $form->field($model, 'billing_phone') ?>
        </div>

    </div>

    <div class="clearfix">
        <?= Html::checkbox('', false, ['id'=>'cbAgree'])?> <?=Yii::t('app','I agree to the <a target="_blank" href="{link}">Terms and Conditions</a> of {store}',['link'=>Url::to(['/page/terminos-y-condiciones']),'store' => $storeName])?>
        <div id="cbAgreeMsg" class="has-error" style="display: none;"><?=Html::tag('div', Yii::t('app', 'You need to agree the Terms and Conditions before register.'), ['class'=>'help-block help-block-error'])?></div>
    </div>

    <div class="form-group" style="margin: 20px;">
        <button type="submit" class="btn btn-primary btn-lg" ><?=Yii::t('app', 'Submit')?></button>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-register -->
