<?php
use app\controllers\BaseSiteController;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var BaseSiteController $controller */
/** @var ActiveForm $form */

$controller = Yii::$app->controller;
$user = $controller->getLoggedUser();
$shopCart = $controller->getShoppingCart();

// destination
$destinations = ArrayHelper::map($user->userShippingAddresses, 'id', 'shipping_contact');
$selected_destination = 0;
if (!empty($shopCart->destination) && array_key_exists($shopCart->destination, $destinations)) $selected_destination = $shopCart->destination;
if (empty($selected_destination) && !is_null($destination = $user->getDefaultDestination())) $selected_destination = $destination->id;

if (!empty($destinations)) { ?>
    <div id="sc-destination-sel" style="<?php if (!is_null($model)) echo 'display:none;'?>">

        <a id="btn-add-destination" href="#" style="float: right;margin: 6px 6px 0 0;"><span class="glyphicon glyphicon-plus"></span> <?= Yii::t('app', 'Add Destination') ?></a>
        <h3>1. <?= Yii::t('app', 'Select Destination') ?></h3>

        <?php ActiveForm::begin(['action' => Url::to(['shopcart/destination-change'])]); ?>
        <div>
            <?php foreach ($user->userShippingAddresses as $shipAdd) { ?>
                    <div class="row sc-destination-option <?php if ($shipAdd->id == $selected_destination) echo "selected" ?>">
                        <div class="col-xs-7">
                            <input type="radio" class="destination_id" name="destination_id" id="destination_id_<?= $shipAdd->id ?>" value="<?= $shipAdd->id ?>" <?php if ($shipAdd->id == $selected_destination) echo "checked" ?> >
                            <label for="destination_id_<?= $shipAdd->id ?>">
                                <?= $shipAdd->shipping_contact ?>
                                <?= !empty($shipAdd->shipping_email) ? Html::tag('span','('.$shipAdd->shipping_email.')', ['style'=>'font-weight:normal;']) : '' ?>
                            </label>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            <a class="btn-destination-edit" href="<?=Url::to(['shopcart/destination-edit', 'id'=>$shipAdd->id])?>"><span class="glyphicon glyphicon-pencil"></span> <?=Yii::t('app','Edit')?></a>
                        </div>
                    </div>
            <?php } ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php } ?>

<div id="sc-destination-new" style="margin-top: 20px;<?php if (is_null($model) && !empty($destinations)) echo "display:none;" ?>">
    <?php
    if (is_null($model)) {
        $model = new \app\models\UserShippingAddress();
    }
    $form = ActiveForm::begin(['action' => Url::to(['shopcart/destination-save']), 'layout' => 'horizontal']);
    echo Html::hiddenInput('id', $model->id);
    ?>
    <h3>1. <?= empty($model->id) ? Yii::t('app', 'Add New Destination') : Yii::t('app', 'Update Destination') ?></h3>
    <?= $form->errorSummary([$model]); ?>

    <div class="row">
        <div class="col-sm-4">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'First and Last Name') ?></div>
            <?= Html::activeTextInput($model, 'shipping_contact', ['class' => 'form-control']) ?>
        </div>
        <div class="col-sm-4">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'Email Address') ?></div>
            <?= Html::activeTextInput($model, 'shipping_email', ['class' => 'form-control']) ?>
        </div>
        <div class="col-sm-4">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'Phone Number') ?></div>
            <?= Html::activeTextInput($model, 'shipping_phone', ['class' => 'form-control']) ?>
        </div>

    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
            <?php if (!empty($model->id)) { ?>
                <a href="<?=Url::to(['shopcart/destination-delete', 'id'=>$model->id])?>" id="btn-destination-delete" class="btn btn-default" style="float: right" ><span class="glyphicon glyphicon-remove"></span> <?= Yii::t('app', 'Delete') ?></a>
            <?php } ?>
            <button type="button" id="btn-add-destination-save" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> <?= Yii::t('app', 'Save Destination') ?></button>
            <?php if (!empty($destinations)) { ?>
                <button type="button" id="btn-add-destination-cancel" class="btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
