<?php

use app\models\Order;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();

?>
<div class="order-view">

    <div class="row">
        <div class="col-xs-6"><h1><?= Yii::t('app', 'Order #') . '' . $model->id ?></h1></div>
        <div class="col-xs-6" style="text-align: right"><h2>
                <?php if ($model->status == Order::STATUS_APPROVED) { ?>

                    <span class="glyphicon glyphicon-ok-sign"></span> Aprobada

                <?php } else if ($model->status == Order::STATUS_REJECTED) { ?>

                    <span class="glyphicon glyphicon-alert"></span> Rechazada

                <?php } else { ?>

                    <span class="glyphicon glyphicon-info-sign"></span> Recibida

                <?php } ?>
            </h2></div>
    </div>

    <div class="order-item" style="">
        <div class="row">
            <div class="col-sm-5">

                <table class="info table  table-bordered">
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Created') ?></th>
                        <td><?= $model->created ?></td>
                    </tr>
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Buyer') ?></th>
                        <td>
                            <div><?= $model->billing_first_name . ' ' . $model->billing_last_name ?></div>
                            <div><?= $model->user->email ?></div>
                            <?= !empty($model->billing_address1) ? Html::tag('div', $model->billing_address1) : '' ?>
                            <?= !empty($model->billing_address2) ? Html::tag('div', $model->billing_address2) : '' ?>
                            <div>
                                <?= !empty($model->billing_city) ? Html::tag('span', $model->billing_city) : '' ?>
                                <?= !empty($model->billing_state) ? Html::tag('span', $model->billing_state) : '' ?>
                                <?= !empty($model->billing_zip) ? Html::tag('span', $model->billing_zip) : '' ?>
                                <?= !empty($model->billing_country) ? Html::tag('span', $model->billing_country) : '' ?>
                            </div>
                            <?= !empty($model->billing_phone) ? Html::tag('span', $model->billing_phone) : '' ?>
                        </td>
                    </tr>

                    <?php if (!is_null($orderPayment)) {
                        $cardInfo = $orderPayment->getCardInfo(); ?>
                        <tr>
                            <th nowrap="nowrap"><?= Yii::t('app', 'Payment') ?></th>
                            <td>
                                <?php if ($orderPayment->status == 1) { ?>
                                    <div style=""><?= Yii::t('app', 'Payment Approved') ?></div>
                                    <?php if (!empty($cardInfo)) { ?>
                                        <div><?= Yii::t('app', 'Credit Card') ?>: <?= Yii::t('app', 'ending in {card}', ['card' => $cardInfo]) ?></div>
                                    <?php } ?>

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
                            <td>
                                <div><?= $orderShipping->shipping_contact . (!empty($orderShipping->shipping_email) ? ' (' . $orderShipping->shipping_email . ')' : '') ?></div>
                                <?= !empty($orderShipping->shipping_email) ? Html::tag('div', $orderShipping->shipping_email) : '' ?>
                                <?= !empty($orderShipping->shipping_phone) ? Html::tag('div', $orderShipping->shipping_phone) : '' ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if (!empty($model->message)) { ?>
                        <tr>
                            <th nowrap="nowrap"><?= Yii::t('app', 'Message') ?></th>
                            <td>
                                <div><?= $model->message ?></div>
                            </td>
                        </tr>
                    <?php } ?>


                </table>

            </div>
            <div class="col-sm-7">

                <table class="info table table-bordered">
                    <thead>
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Items') ?></th>
                        <th style="text-align: right"><?= Yii::t('app', 'Cost') ?></th>
                        <th style="text-align: right"><?= Yii::t('app', 'Quantity') ?></th>
                        <th style="text-align: right"><?= Yii::t('app', 'Subtotal') ?></th>
                        <?php if ($model->status == Order::STATUS_APPROVED) { ?>
                        <th style="text-align: right"><?= Yii::t('app', 'Status') ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model->orderProducts as $index => $it) {
                        $itProd = $it->product; ?>
                        <tr>
                            <td><?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:24px;']) ?> <?= $it->description ?></td>
                            <td style="text-align: right">$<?= $it->price ?></td>
                            <td style="text-align: right"><?= $it->quantity ?></td>
                            <td style="text-align: right">$<?= number_format($it->getSubtotal(), 2, '.', '') ?></td>
                            <?php if ($model->status == Order::STATUS_APPROVED) { ?>
                            <td style="text-align: right"><?= $it->quantity==$it->consumed ? Yii::t('app','Completed') : Yii::t('app','{p} pending', ['p' => $it->quantity - $it->consumed])  ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr style="font-size: 14pt;">
                        <th>Total</th>
                        <th colspan="3" style="text-align: right;color:#AA0000;">$<?= number_format($model->total, 2, '.', '') ?></th>
                    </tr>
                    </tfoot>
                </table>


        </div>
    </div>
</div>


<p>

</p>


</div>
