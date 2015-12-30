<?php
/** @var $model Order */

use app\models\Order;

$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();

echo $this->render('_top');
?>

<?php if ($model->status == Order::STATUS_APPROVED) { ?>

    <h2>Orden Aprobada</h2>
    <p>El pago de la orden ha sido aprobado. </p>

<?php } else if ($model->status == Order::STATUS_REJECTED) { ?>

    <h2>Orden Rechazada</h2>
    <p>El pago de la orden ha sido rechazado. </p>

<?php } else { ?>

    <h2>Orden Pendiente</h2>
    <p>No se proceso el pago de la orden. Debera actualizar el estado de la misma de forma manual.</p>

<?php } ?>

    <table class="info">
        <tr>
            <th nowrap="nowrap"><?= Yii::t('app', 'Order #') ?></th>
            <td><?= $model->id ?></td>
        </tr>
        <tr>
            <th nowrap="nowrap"><?= Yii::t('app', 'Created') ?></th>
            <td><?= $model->created ?></td>
        </tr>
        <tr>
            <th nowrap="nowrap"><?= Yii::t('app', 'Buyer') ?></th>
            <td><?= $model->billing_first_name . ' ' . $model->billing_last_name. ' (' . $model->user->email . ')' ?></td>
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
            <th nowrap="nowrap"><?= Yii::t('app', 'Items') ?></th>
            <td>
                <table>
                    <?php foreach ($model->orderProducts as $index => $it) {
                        $itProd = $it->product; ?>
                        <tr>
                            <td style="padding: 1px 6px;width: 1%;"><?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:32px;']) ?></td>
                            <td style="vertical-align: middle;"><?= $it->description ?></td>
                            <td style="vertical-align: middle;text-align: center;width: 10%;"><?= $it->quantity ?></td>
                            <td style="text-align: right;vertical-align: middle;color:#AA0000;width: 10%;">$<?= number_format($it->getSubtotal(), 2, '.', '') ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>

        <tr>
            <th nowrap="nowrap"><?= Yii::t('app', 'Total') ?></th>
            <td style="color:#AA0000;font-weight: bold;">$<?= number_format($model->total, 2, '.', '') ?></td>
        </tr>

    </table>

<?php echo $this->render('_bottom');
