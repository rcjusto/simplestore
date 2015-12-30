<?php
/* @var $this yii\web\View */
/* @var $model Order */

use app\models\Order;
use app\models\Provider;
use yii\bootstrap\Html;

$orderShipping = $model->orderShipping;
$orderPayment = $model->getOrderPayment();

?>
<div class="row">
    <div class="col-sm-6">
        <h1><?= Yii::t('app', 'Order #') ?> <?= $model->id ?></h1>
    </div>
    <div class="col-sm-6" style="text-align: right;padding-top: 10px;">
        <?php if ($model->status == Order::STATUS_APPROVED) { ?>

            <h3><span class="glyphicon glyphicon-ok-sign"></span> Orden Aprobada</h3>

        <?php } else if ($model->status == Order::STATUS_REJECTED) { ?>

            <h3 style="color:#AA0000;"><span class="glyphicon glyphicon-alert"></span> Orden Rechazada</h3>

        <?php } else { ?>

            <h3><span class="glyphicon glyphicon-info-sign"></span> Orden Recibida</h3>

        <?php } ?>
    </div>
</div>

<p>
    <span style="font-weight: bold"><?= Yii::t('app', 'Created') ?>:</span>
    <span><?= $model->created ?></span>
</p>

<p>
    <span style="font-weight: bold"><?= Yii::t('app', 'Buyer') ?>:</span>
    <span><?= $model->getBuyerName() ?></span>
</p>

<?php if (!empty($model->message)) { ?>
    <p>
        <span style="font-weight: bold"><?= Yii::t('app', 'Message') ?>:</span>
        <span><?= $model->message ?></span>
    </p>
<?php } ?>

<?php
foreach ($model->getProductsByProviders() as $provId => $items) {
    /** @var Provider $provider */
    $provider = Provider::findOne($provId);
    ?>

    <div style="padding: 2px 5px 12px 5px;border-bottom: 1px dotted #cccccc;">

        <?= (!is_null($provider) && !empty($provider->name)) ? Html::tag('h3', $provider->name, []) : '' ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th><?= Yii::t('app', 'Items') ?></th>
                <th style="text-align: center;width: 10%;"><?= Yii::t('app', 'Quantity') ?></th>
                <th style="text-align: center;width: 25%"><?= Yii::t('app', 'Consumed Items') ?></th>
                <th style="text-align: center;width: 18%"><?= Yii::t('app', 'Pending Items') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $index => $it) {
                $itProd = $it->product; ?>
                <tr>
                    <td style="font-size: 12pt;vertical-align: middle;">
                        <?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:32px;']) ?>
                        <?= $it->description ?>
                    </td>
                    <td style="font-size: 12pt;vertical-align: middle;text-align: center;"><?= $it->quantity ?></td>
                    <td style="font-size: 12pt;text-align: center;vertical-align: middle;color: #008800;">
                        <?php if ($it->consumed > 0) { ?>
                            <span class="glyphicon glyphicon-ok-sign"></span> <?= Yii::t('app', '{num} item(s), on {date}', ['num' => $it->consumed, 'date' => date('Y-m-d', strtotime($it->consumed_date))]) ?>
                        <?php } ?>
                    </td>
                    <td style="font-size: 12pt;text-align: center;vertical-align: middle;color: #AA0000;">
                        <?php if ($it->quantity > $it->consumed) { ?>
                            <span class="glyphicon glyphicon-info-sign"></span> <?= Yii::t('app', '{num} pending', ['num' => $it->quantity - $it->consumed]) ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?= (!is_null($provider) && !empty($provider->destination_instructions)) ? Html::tag('div', $provider->destination_instructions, []) : '' ?>

    </div>

<?php } ?>
<script>
    window.print();
</script>