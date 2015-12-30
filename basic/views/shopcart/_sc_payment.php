<?php
/** @var BaseSiteController $controller */
/** @var ActiveForm $form */

use app\controllers\BaseSiteController;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$controller = Yii::$app->controller;
$user = $controller->getLoggedUser();
$shopCart = $controller->getShoppingCart();

// payment methods
$payments = ArrayHelper::map($user->userAuthorizeNet, 'id', 'cc_info');
$selected_payment = 0;
if (!empty($shopCart->payment_method) && array_key_exists($shopCart->payment_method, $payments)) $selected_payment = $shopCart->payment_method;
if (empty($selected_payment) && count($payments) > 0) $selected_payment = array_keys($payments)[0];

$months = [
    '01' => '01 ' . Yii::t('app', 'January'),
    '02' => '02 ' . Yii::t('app', 'February'),
    '03' => '03 ' . Yii::t('app', 'March'),
    '04' => '04 ' . Yii::t('app', 'April'),
    '05' => '05 ' . Yii::t('app', 'May'),
    '06' => '06 ' . Yii::t('app', 'June'),
    '07' => '07 ' . Yii::t('app', 'July'),
    '08' => '08 ' . Yii::t('app', 'August'),
    '09' => '09 ' . Yii::t('app', 'September'),
    '10' => '10 ' . Yii::t('app', 'October'),
    '11' => '11 ' . Yii::t('app', 'November'),
    '12' => '12 ' . Yii::t('app', 'December'),
];

$year = intval(date('Y'));
$years = [];
for ($i = 0; $i < 20; $i++) $years[$year + $i] = $year + $i;

if (!empty($payments)) { ?>
    <div id="sc-payment-sel" style="<?php if (!is_null($model)) echo 'display:none;' ?>">
        <a id="btn-add-payment" href="#" style="float: right;margin: 6px 6px 0 0;"><span class="glyphicon glyphicon-plus"></span> <?= Yii::t('app', 'Add Payment Method') ?></a>
        <h3>2. <?= Yii::t('app', 'Select Payment Method') ?></h3>

        <?php ActiveForm::begin(['action' => Url::to(['shopcart/payment-change'])]); ?>
        <div class="form-group">
            <?php foreach ($payments as $paymentID => $paymentName) if (!empty($paymentName)) { ?>

                <div class="row sc-destination-option <?php if ($paymentID == $selected_payment) echo "selected" ?>">
                    <div class="col-xs-8">
                        <input type="radio" id="payment_id_<?= $paymentID ?>" name="payment_id" class="payment_id" value="<?= $paymentID ?>" <?php if ($paymentID == $selected_payment) echo "checked" ?> >
                        <label for="payment_id_<?= $paymentID ?>"><?= Yii::t('app', 'Credit card ending in {card}', ['card' => $paymentName]) ?></label>
                    </div>
                    <div class="col-xs-4" style="text-align: right">
                        <a class="btn-payment-edit" href="<?= Url::to(['shopcart/payment-delete', 'id' => $paymentID]) ?>"><span class="glyphicon glyphicon-trash"></span> <?=Yii::t('app','Delete')?></a>
                    </div>
                </div>

            <?php } ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php } ?>

<div id="sc-payment-new" style="margin-top: 20px;<?php if (is_null($model) && !empty($payments)) echo "display:none;" ?>">
    <?php
    if (!isset($model) || is_null($model)) {
        $model = new \app\models\CreditCardForm();
    }
    $form = ActiveForm::begin(['action' => Url::to(['shopcart/payment-save']), 'layout' => 'horizontal']);
    echo Html::activeHiddenInput($model, 'profile_id');
    echo Html::activeHiddenInput($model, 'payment_profile_id');
    ?>
    <h3>2. <?= Yii::t('app', 'Add New Payment Method') ?></h3>
    <?= $form->errorSummary([$model]); ?>

    <div class="row">
        <div class="col-sm-5">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'Credit Card') ?></div>
            <?= Html::activeTextInput($model, 'credit_card', ['class' => 'form-control']) ?>
        </div>
        <div class="col-sm-4">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'Expiration Date') ?></div>
            <?= Html::activeDropDownList($model, 'expiration_month', $months, ['class' => 'form-control', 'style' => 'width:60%;display:inline-block;padding-left:6px;']) ?>
            <?= Html::activeDropDownList($model, 'expiration_year', $years, ['class' => 'form-control', 'style' => 'width:38%;display:inline-block;padding-left:6px;']) ?>
        </div>
        <div class="col-sm-3">
            <div style="margin-bottom: 4px;"><?= Yii::t('app', 'Security Code') ?></div>
            <?= Html::activeTextInput($model, 'security_code', ['class' => 'form-control']) ?>
        </div>
    </div>


    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
            <button type="button" id="btn-add-payment-save" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> <?= Yii::t('app', 'Save Credit Card') ?></button>
            <?php if (!empty($payments)) { ?>
                <button type="button" id="btn-add-payment-cancel" class="btn btn-defaul"><?= Yii::t('app', 'Cancel') ?></button>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>