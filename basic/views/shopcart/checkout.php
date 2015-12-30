<?php
/** @var BaseSiteController $controller */

use app\controllers\BaseSiteController;
use yii\bootstrap\ActiveForm;

/** @var BaseSiteController $controller */
$controller = Yii::$app->controller;
$sc = $controller->getShoppingCart();
$numProds = $sc->getNumProducts();
$textItems = Yii::t('app', 'Empty');
if ($numProds == 1) $textItems = '1 ' . Yii::t('app', 'item');
else if ($numProds > 1) $textItems = $numProds . ' ' . Yii::t('app', 'items');
?>

<div class="site-checkout">

    <h1><?= Yii::t('app', 'Checkout') ?></h1>

    <div class="row">
        <div class="col-md-12">
            <div id="blockDestination" style="margin-bottom: 30px;">
                <?= $this->render('_sc_destination', ['model' => null]) ?>
            </div>
            <div id="blockPayment" style="margin-bottom: 30px;">
                <?= $this->render('_sc_payment', ['model' => null, 'id' => null]) ?>
            </div>
            <div style="margin-bottom: 30px;">
                <a href="<?= \yii\helpers\Url::to(['index']) ?>" style="float: right;margin: 6px 6px 0 0;"><span class="glyphicon glyphicon-chevron-left"></span> <?= Yii::t('app', 'Back to cart') ?></a>

                <h3>3. <?= Yii::t('app', 'Review items') ?></h3>

                <div class="clearfix">
                    <?php foreach ($sc->items as $index => $it) {
                        $itProd = $it->getProduct(); ?>
                        <div class="checkout-item" style="">
                            <div style="padding: 10px;margin: 5px;">
                                <div class="clearfix">
                                    <img src="<?= $itProd->getListImage() ?>" style="float: left;max-height: 72px;margin-right: 5%;width:35%">
                                    <div style="float: left;height: 72px;overflow: hidden;width:59%">
                                        <div style="line-height: 16px;height: 32px;font-weight: bold;"><?= $itProd->getName(Yii::$app->language) ?></div>
                                        <div><?= Yii::t('app', 'Quantity: {qty}', ['qty' => $it->quantity]) ?></div>
                                        <div><?= Yii::t('app', 'Price: ${price}', ['price' => number_format($it->price, 2, '.', '')]) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <?php ActiveForm::begin(['action' => \yii\helpers\Url::to(['place-order'])]) ?>
            <div style="margin-bottom: 30px;">

                <h3>4. <?= Yii::t('app', 'Add a message to your order') ?> <span style="font-size: 12pt;"><?=Yii::t('app','(optional)')?></span></h3>

                <div><textarea class="form-control" name="message"></textarea></div>

            </div>

            <div id="blockConfirm" style="border-top: 1px solid #dddddd;padding-top: 30px;margin-top: 30px;">
                <div class="row">
                    <div class="col-xs-6" style="text-align: right">
                        <div style="font-size: 14pt;line-height: 20px"><?= $textItems ?></div>
                        <div style="font-size: 14pt;font-weight: bold;">
                            <?= Yii::t('app', 'Order Total') ?>: <span style="color:#AA0000;">$<?= number_format($sc->getTotal(), 2, '.', '') ?></span>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <input type="hidden" id="destination_id" name="destination_id" value="">
                        <input type="hidden" id="payment_id" name="payment_id" value="">
                        <button type="submit" class="btn btn-primary btn-lg" onclick="$(this).hide();"><?=Yii::t('app','PLACE YOUR ORDER')?></button>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>

    </div>

</div>
