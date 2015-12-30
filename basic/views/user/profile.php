<?php
use app\models\Nicaragua;
use app\models\User;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var $user User */

?>
<div class="site-register">

    <h2><?= Yii::t('app', 'My Profile') ?></h2>

    <div class="row form">

        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

            <h3><?= Yii::t('app', 'Personal Information') ?></h3>
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'billing_address1') ?>
            <?= $form->field($model, 'billing_city') ?>
            <?= $form->field($model, 'billing_state') ?>
            <?= $form->field($model, 'billing_zip') ?>
            <?= $form->field($model, 'billing_country')->dropDownList(Nicaragua::$countries) ?>
            <?= $form->field($model, 'billing_phone') ?>
            <h3><?= Yii::t('app', 'Change Your Password') ?></h3>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <div class="col-sm-3">&nbsp;</div>
                <div class="col-sm-6">
                    <?= Html::submitButton(Yii::t('app', 'Update Profile'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-lg-6" id="blockProfileDestinations">
            <h3><?= Yii::t('app', 'My Destinations') ?></h3>
            <?php foreach($user->userShippingAddresses as $add) { ?>
                <div style="padding: 10px 20px; border-top: 1px solid #dddddd;" class="destination-container">
                    <?= $this->render('_destination', ['model' => $add])?>
                </div>
            <?php } ?>
            <div style="padding: 10px 20px; border-top: 1px solid #dddddd;" class="destination-container">
                <a class="destination_edit btn btn-primary" href="<?=Url::to(['user/destination-create'])?>"><?=Yii::t('app','Add Destination')?></a>
            </div>
        </div>

    </div>

</div>
