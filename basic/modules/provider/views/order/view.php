<?php

use app\models\Order;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();
$provider_id = Yii::$app->controller->getProvider()->id;
$orderTime = strtotime($model->created);
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
                        </td>
                    </tr>

                    <?php if (!is_null($orderShipping)) { ?>
                        <tr>
                            <th nowrap="nowrap"><?= Yii::t('app', 'Destination') ?></th>
                            <td>
                                <div><?= $orderShipping->shipping_contact  ?></div>
                                <?= !empty($orderShipping->shipping_email) ? Html::tag('div', $orderShipping->shipping_email) : '' ?>
                                <?= !empty($orderShipping->shipping_phone) ? Html::tag('div', $orderShipping->shipping_phone) : '' ?>
                            </td>
                        </tr>
                    <?php } ?>


                </table>

            </div>
            <div class="col-sm-7">
                <?php ActiveForm::begin(['action'=>Url::to(['/provider/order/update-consumed', 'id'=>$model->id])])?>
                <table class="info table table-bordered">
                    <thead>
                    <tr>
                        <th nowrap="nowrap"><?= Yii::t('app', 'Items') ?></th>
                        <th style="text-align: right"><?= Yii::t('app', 'Quantity') ?></th>
                        <th style="text-align: right"><?= Yii::t('app', 'Subtotal') ?></th>
                        <?php if ($model->status == Order::STATUS_APPROVED) { ?>
                        <th style="text-align: center"><?= Yii::t('app', 'Consumed') ?></th>
                        <th style="text-align: center"><?= Yii::t('app', 'When') ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model->getProviderProducts($provider_id) as $index => $it) {
                        $itProd = $it->product; ?>
                        <tr>
                            <td><?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:24px;']) ?> <?= $it->description ?></td>
                            <td style="text-align: right"><?= $it->quantity ?></td>
                            <td style="text-align: right">$<?= number_format($it->cost * $it->quantity, 2, '.', '') ?></td>
                            <?php if ($model->status == Order::STATUS_APPROVED) { ?>
                            <td style="text-align: right">
                                <select name="order_products[<?=$it->id?>]" class="form-control">
                                <?php
                                for($i=0; $i <= $it->quantity; $i++) {
                                    $options = ['value'=>$i];
                                    if ($it->consumed == $i) $options['selected'] = 'selected';
                                    echo Html::tag('option', $i, $options);
                                }
                                ?>
                                </select>
                            </td>
                            <td style="max-width: 120px;">
                                <?php  $dd = ($it->consumed>0 && !empty($it->consumed_date)) ? date('Y-m-d',strtotime($it->consumed_date)) : ''; ?>
                                <input class="readonly form-control date-select" name="order_consumed[<?=$it->id?>]" readonly="readonly" type="text" value="<?=$dd?>" data-year="<?=date('Y',$orderTime)?>" data-month="<?=date('m',$orderTime)-1?>" data-day="<?=date('d',$orderTime)?>">
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr style="font-size: 14pt;">
                        <th>Total</th>
                        <th colspan="2" style="text-align: right;color:#AA0000;">$<?= number_format($model->getProviderCost($provider_id), 2, '.', '') ?></th>
                        <?php if ($model->status == Order::STATUS_APPROVED) { ?>
                        <td style="text-align: center"><button type="submit" class="btn btn-primary"><?= Yii::t('app','Update')?></button></td>
                        <?php } ?>
                    </tr>
                    </tfoot>
                </table>
                <?php ActiveForm::end()?>
        </div>
    </div>
</div>


<p>

</p>


</div>
