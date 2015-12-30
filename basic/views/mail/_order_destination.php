<?php
/** @var $model Order */
/** @var $provider Order */

use app\models\Order;
use app\models\Provider;
use yii\helpers\Url;

$orderShipping = $model->orderShipping;
$destinationAccount = $orderShipping->getDestinationAccount();
$url_destination = Url::base(true) . '/destinatario/';

$instructions = [];
foreach ($providers as $provID) {
    if (!is_null($provider = Provider::findOne($provID))) {
        if (!empty($provider->destination_instructions)) $instructions[] = $provider->destination_instructions;
    }
}


echo $this->render('_top');
?>

    <h2>Confirmaci&oacute;n de Compra</h2>

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

        <?php if (!empty($model->message)) { ?>
            <tr>
                <th nowrap="nowrap"><?= Yii::t('app', 'Message') ?></th>
                <td><?= $model->message ?></td>
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
                            <td style="vertical-align: middle;text-align: right;width: 10%;"><?= $it->quantity ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>

    </table>

    <p></p>

    <div>
        <?php foreach ($instructions as $inst) { ?>
            <div><?= $inst ?></div>
        <?php } ?>
    </div>

<?php if (!is_null($destinationAccount)) { ?>
    <p>Usted puede ver todas las compras recibidas en <a href="<?= $url_destination ?>"><?= $url_destination ?></a></p>
    <?php if (empty($destinationAccount->last_login)) { ?>
        <p>Para acceder use las siguientes credenciales:</p>
        <div style="text-align: center">
            <div style="display: inline-block;padding: 20px;background-color: #cae4fb;">
                <table style="width: auto;">
                    <tr>
                        <td style="padding: 6px 12px;text-align: right;"><?= Yii::t('app', 'Email address') ?></td>
                        <td style="padding: 6px 12px;background-color: #ffffff;font-weight: bold;font-family: monospace;"><?= $destinationAccount->email ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 4px;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 12px;text-align: right;"><?= Yii::t('app', 'Password') ?></td>
                        <td style="padding: 6px 12px;background-color: #ffffff;font-weight: bold;font-family: monospace;"><?= $destinationAccount->password ?></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php } ?>
<?php } ?>

    <p style="margin-top: 40px;">Gracias por comprar en nuestra tienda.</p>
    <p><?= $storeName ?></p>

<?php echo $this->render('_bottom');
