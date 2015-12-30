<?php

/** @var $model Order */
use app\models\Order;
use yii\bootstrap\Html;

$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();

?>

<div class="order-item" style="">
    <div class="row">
        <div class="col-sm-6">
            <h3><?= Yii::t('app', 'Order #') . '' . $model->id ?>

                <?php if ($model->status == Order::STATUS_APPROVED) { ?>

                    <span style="margin-left:20px;font-size: 14pt;"><span class="glyphicon glyphicon-ok-sign"></span> Aprobada</span>

                <?php } else if ($model->status == Order::STATUS_REJECTED) { ?>

                    <span style="margin-left:20px;font-size: 14pt;color:#AA0000;"><span class="glyphicon glyphicon-alert"></span> Rechazada</span>

                <?php } else { ?>

                    <span style="margin-left:20px;font-size: 14pt;"><span class="glyphicon glyphicon-info-sign"></span> Recibida</span>

                <?php } ?>
            </h3>
        </div>
        <div class="col-sm-6">
            <div style="margin: 24px 0 0 0;text-align: center">
                <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . Yii::t('app','Order Details'), ['user/order', 'id'=>$model->id])?>
                &nbsp;&nbsp;
                <?= Html::a('<span class="glyphicon glyphicon-repeat"></span> ' .Yii::t('app','Add products to Cart'), ['user/re-order', 'id'=>$model->id])?>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <table class="info">
                <tr>
                    <th nowrap="nowrap"><?= Yii::t('app', 'Created') ?></th>
                    <td><?= $model->created ?></td>
                </tr>
                <tr>
                    <th nowrap="nowrap"><?= Yii::t('app', 'Buyer') ?></th>
                    <td><?= $model->billing_first_name . ' ' . $model->billing_last_name . ' (' . $model->user->email . ')' ?></td>
                </tr>

                <?php if (!is_null($orderPayment)) {
                    $cardInfo = $orderPayment->getCardInfo(); ?>
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Payment') ?></th>
                        <td>
                            <?php if ($orderPayment->status == 1) { ?>
                                <div style=""><?= Yii::t('app', 'Payment Approved') ?>:
                                    <?php if (!empty($cardInfo)) { ?>
                                        <span><?= Yii::t('app', 'Credit Card') ?>: <?= Yii::t('app', 'ending in {card}', ['card' => $cardInfo]) ?>,</span>
                                    <?php } ?>
                                </div>
                                <div><?= Yii::t('app', 'Authorization Code') ?>: <?= $orderPayment->authorization_code ?>,</div>
                                <div><?= Yii::t('app', 'Transaction ID') ?>: <?= $orderPayment->transaction_id ?></div>
                            <?php } else { ?>
                                <div style=""><?= Yii::t('app', 'Payment Rejected') ?>:
                                    <?php if (!empty($cardInfo)) { ?>
                                        <span><?= Yii::t('app', 'Credit Card') ?>: <?= Yii::t('app', 'ending in {card}', ['card' => $cardInfo]) ?></span>
                                    <?php } ?>
                                </div>
                                <div><?= $orderPayment->message ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

                <?php if (!is_null($orderShipping)) { ?>
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Destination') ?></th>
                        <td><?= $orderShipping->shipping_contact . (!empty($orderShipping->shipping_email) ? ' (' . $orderShipping->shipping_email . ')' : '') ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th nowrap="nowrap"><?= Yii::t('app', 'Total') ?></th>
                    <td style="color:#AA0000;font-weight: bold;">$<?= number_format($model->total, 2, '.', '') ?></td>
                </tr>

                <?php if (!empty($model->message)) { ?>
                <tr>
                    <th nowrap="nowrap"><?= Yii::t('app', 'Message') ?></th>
                    <td><?= strlen($model->message)>280 ? substr($model->message, 0 ,260) . ' ...' : $model->message ?></td>
                </tr>
                <?php } ?>

            </table>

        </div>
        <div class="col-sm-6">
            <?php foreach ($model->orderProducts as $index => $it) {
                $itProd = $it->product; ?>
                <div class="clearfix" style="margin: 15px;">
                    <div style="float: left;margin-right: 20px">
                        <?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:72px;']) ?>
                    </div>
                    <div style="float: left;font-size: 10pt;">
                        <div style="font-size: 12pt;margin: 6px 0;"><?= $it->description ?></div>
                        <div>Quantity: <?= $it->quantity ?></div>
                        <div>Price: $<?= number_format($it->getSubtotal(), 2, '.', '') ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>