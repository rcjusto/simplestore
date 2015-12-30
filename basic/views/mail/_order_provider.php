<?php
/** @var $model Order */
/** @var $provider Provider */

use app\models\Order;
use app\models\Provider;
use yii\helpers\Url;

$orderShipping = $model->orderShipping;
$url_provider = Url::base(true) . '/provider/';

echo $this->render('_top');
?>

    <h2>Orden de Compra</h2>
    <?php if (!is_null($provider)) { ?>
        <h3>Proveedor: <?=$provider->name  ?></h3>
    <?php } else { ?>
        <h3>Productos sin proveedor</h3>
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
            <td><?= $model->billing_first_name . ' ' . $model->billing_last_name ?></td>
        </tr>
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
                    <?php
                    $total = 0;
                    foreach ($items as $index => $it) {
                        $itProd = $it->product;
                        $total += $it->quantity * $it->cost;
                        ?>
                        <tr>
                            <td style="padding: 1px 6px;width: 1%;"><?php if (!is_null($itProd)) echo \yii\bootstrap\Html::img($itProd->getListImage(), ['style' => 'max-height:32px;']) ?></td>
                            <td style="vertical-align: middle;"><?= $it->description ?></td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;"><?= $it->quantity ?></td>
                            <td style="vertical-align: middle;text-align: right;width: 10%;"><?= $it->cost ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
        <tr>
            <th nowrap="nowrap"><?= Yii::t('app', 'Total') ?></th>
            <td style="color:#AA0000;font-weight: bold;">$<?= number_format($total, 2, '.', '') ?></td>
        </tr>
    </table>



<?php echo $this->render('_bottom');
