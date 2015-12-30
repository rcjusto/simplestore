<?php
/** @var $model Order */

use app\components\StoreUtils;
use app\models\Order;

$language = Yii::$app->language;
$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();


?>
<div class="checkout-result">

    <?php if ($model->status == Order::STATUS_APPROVED) { ?>

        <div class="row">
            <div class="col-sm-6">
                <h2><span class="glyphicon glyphicon-ok-sign"></span> <?= Yii::t('app', 'Order Confirmation') ?></h2>
            </div>
            <div class="col-sm-6" style="text-align: right;margin-top: 10px;">
                <h3><?= Yii::t('app', 'Order # {order}', ['order' => $model->id]) ?></h3>
            </div>
        </div>
        <div style="margin-left: 40px;margin-bottom: 30px;">
            <?= StoreUtils::getBlockContent('order_approved_message', $language) ?>
        </div>

    <?php } else if ($model->status == Order::STATUS_REJECTED) { ?>

        <div class="row">
            <div class="col-sm-6">
                <h2><span class="glyphicon glyphicon-ok-sign"></span> <?= Yii::t('app', 'Order Rejected') ?></h2>
            </div>
            <div class="col-sm-6" style="text-align: right;margin-top: 10px;">
                <h3><?= Yii::t('app', 'Order # {order}', ['order' => $model->id]) ?></h3>
            </div>
        </div>
        <div style="margin-left: 40px;margin-bottom: 30px;">
            <?= StoreUtils::getBlockContent('order_rejected_message', $language) ?>
        </div>

    <?php } else { ?>

        <div class="row">
            <div class="col-sm-6">
                <h2><span class="glyphicon glyphicon-ok-sign"></span> <?= Yii::t('app', 'Order Pending') ?></h2>
            </div>
            <div class="col-sm-6" style="text-align: right;margin-top: 10px;">
                <h3><?= Yii::t('app', 'Order # {order}', ['order' => $model->id]) ?></h3>
            </div>
        </div>
        <div style="margin-left: 40px;margin-bottom: 30px;">
            <?= StoreUtils::getBlockContent('order_pending_message', $language) ?>
        </div>

    <?php } ?>


    <div style="margin-left: 40px;">
        <?php if (!is_null($orderPayment)) {
            $cardInfo = $orderPayment->getCardInfo(); ?>
            <p>
                <?php if ($orderPayment->status == 1) { ?>
                    <span style="font-weight: bold"><?= Yii::t('app', 'Payment Approved') ?>:</span>
                    <?php if (!empty($cardInfo)) { ?>
                        <span><?= Yii::t('app', 'Credit Card') ?>: <?= Yii::t('app', 'ending in {card}', ['card' => $cardInfo]) ?>,</span>
                    <?php } ?>
                    <span><?= Yii::t('app', 'Authorization Code') ?>: <?= $orderPayment->authorization_code ?>,</span>
                    <span><?= Yii::t('app', 'Transaction ID') ?>: <?= $orderPayment->transaction_id ?></span>
                <?php } else { ?>
                    <span style="font-weight: bold"><?= Yii::t('app', 'Payment Rejected') ?>:</span>
                    <?php if (!empty($cardInfo)) { ?>
                        <span><?= Yii::t('app', 'Credit Card') ?>: <?= Yii::t('app', 'ending in {card}', ['card' => $cardInfo]) ?></span>
                    <?php } ?>
                    <span><?= $orderPayment->message ?></span>
                <?php } ?>
            </p>
        <?php } ?>

        <?php if (!is_null($orderShipping)) { ?>
            <p>
                <span style="font-weight: bold"><?= Yii::t('app', 'Destination') ?>:</span>
                <span><?= $orderShipping->shipping_contact . (!empty($orderShipping->shipping_email) ? ' (' . $orderShipping->shipping_email . ')' : '') ?></span>
            </p>
        <?php } ?>

        <table class="table">
            <thead>
            <tr>
                <th colspan="2" style="padding-left: 0;font-size: 12pt;"><?= Yii::t('app', 'Items') ?></th>
                <th style="text-align: right;padding-right: 30px;"><?= Yii::t('app', 'Price') ?></th>
                <th style="text-align: center"><?= Yii::t('app', 'Quantity') ?></th>
                <th style="text-align: right"><?= Yii::t('app', 'Subtotal') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->orderProducts as $index => $it) {
                $itProd = $it->product; ?>
                <tr>
                    <td style="padding: 1px 6px;width: 1%;"><?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:32px;']) ?></td>
                    <td style="font-size: 12pt;vertical-align: middle;"><?= $it->description ?></td>
                    <td style="font-size: 12pt;text-align: right;vertical-align: middle;padding-right: 30px;color:#AA0000;width: 10%;">$<?= number_format($it->price, 2, '.', '') ?></td>
                    <td style="vertical-align: middle;text-align: center;width: 10%;"><?= $it->quantity ?></td>
                    <td style="font-size: 12pt;text-align: right;vertical-align: middle;color:#AA0000;width: 10%;">$<?= number_format($it->getSubtotal(), 2, '.', '') ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr style="font-size: 14pt;font-weight: bold;">
                <td colspan="2" style="border-top: 2px solid #666666;"><span style="font-weight: bold;"><?= Yii::t('app', 'Total') ?></span></td>
                <td colspan="3" style="border-top: 2px solid #666666;text-align: right;">
                    <span style="color:#AA0000;">$<?= number_format($model->total, 2, '.', '') ?></span>
                </td>
            </tr>
            </tfoot>
        </table>

        <?php
        if ($model->status == Order::STATUS_APPROVED) {
            echo StoreUtils::getBlockContent('order_approved_thanks', $language);
        } else if ($model->status == Order::STATUS_REJECTED) {
            echo StoreUtils::getBlockContent('order_rejected_thanks', $language);
        } else {
            echo StoreUtils::getBlockContent('order_pending_thanks', $language);
        }
        ?>
    </div>
</div>
