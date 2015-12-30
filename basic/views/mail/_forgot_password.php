<?php
/** @var $user User */

use app\models\Property;
use app\models\User;
use yii\helpers\Url;

$storeName = Property::getPropertyValue('store_name', '');
$url = Url::to(['site/index'], true);

echo $this->render('_top');
?>

    <h3>Hola <?= $user->getFullname() ?></h3>
    <p>Para acceder a nuestro sitio use las siguientes credenciales:</p>
    <div style="display: inline-block;padding: 20px;background-color: #cae4fb;">
        <table style="width: auto;">
            <tr>
                <td style="padding: 6px 12px;text-align: right;"><?= Yii::t('app', 'Email address') ?></td>
                <td style="padding: 6px 12px;background-color: #ffffff;font-weight: bold;font-family: monospace;"><?= $user->username ?></td>
            </tr>
            <tr>
                <td colspan="2" style="height: 4px;"></td>
            </tr>
            <tr>
                <td style="padding: 6px 12px;text-align: right;"><?= Yii::t('app', 'Password') ?></td>
                <td style="padding: 6px 12px;background-color: #ffffff;font-weight: bold;font-family: monospace;"><?= $user->password ?></td>
            </tr>
        </table>
    </div>
    <p>Muchas gracias por usar nuestros servicios.</p>
    <p><?=$storeName?></p>

<?php echo $this->render('_bottom');
