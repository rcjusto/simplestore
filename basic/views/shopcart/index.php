<?php
use app\components\StoreUtils;
use app\controllers\BaseSiteController;
use yii\widgets\ActiveForm;

/** @var BaseSiteController $controller */
$controller = Yii::$app->controller;
$sc = $controller->getShoppingCart();
$numProds = $sc->getNumProducts();
$textItems = Yii::t('app', 'Empty');
if ($numProds == 1) $textItems = '1 ' . Yii::t('app', 'item');
else if ($numProds > 1) $textItems = $numProds . ' ' . Yii::t('app', 'items');


?>
<div class="site-shopcart">

    <?php if (count($sc->items) > 0) { ?>
        <table class="table">
            <thead>
            <tr>
                <th colspan="2"><h2><?= Yii::t('app', 'Shopping Cart') ?></h2></th>
                <th style="text-align: right;padding-right: 30px;"><?= Yii::t('app', 'Price') ?></th>
                <th style="text-align: center"><?= Yii::t('app', 'Quantity') ?></th>
                <th style="text-align: right"><?= Yii::t('app', 'Subtotal') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sc->items as $index => $it) {
                $itProd = $it->getProduct(); ?>
                <tr>
                    <td style="padding: 1px 6px;width: 1%;"><img src="<?= $itProd->getListImage() ?>" style="max-height: 54px;"></td>
                    <td style="font-size: 12pt;vertical-align: middle;">
                        <?= $itProd->getName(Yii::$app->language) ?>
                        <div style="font-size: 8pt;margin-top: 6px;">
                            <a style="display: inline-block;margin-right: 12px;" href="<?= \yii\helpers\Url::to(['shopcart/del-item', 'index' => $index]) ?>"><span class="glyphicon glyphicon-remove"></span> <?= Yii::t('app', 'Remove from cart') ?></a>
                        </div>
                    </td>
                    <td style="font-size: 12pt;text-align: right;vertical-align: middle;padding-right: 30px;color:#AA0000">$<?= number_format($it->price, 2, '.', '') ?></td>
                    <td style="vertical-align: middle">
                        <?php ActiveForm::begin(['action' => \yii\helpers\Url::to(['shopcart/update-item', 'index' => $index])]) ?>
                        <?= \yii\bootstrap\Html::dropDownList('quantity', $it->quantity, [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9], ['class' => 'form-control update-cart-item']) ?>
                        <?php ActiveForm::end() ?>
                    </td>
                    <td style="font-size: 12pt;text-align: right;vertical-align: middle;color:#AA0000">$<?= number_format($it->getSubtotal(), 2, '.', '') ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" style="font-size: 13pt;text-align: right;">
                    <span style="font-weight: bold;"><?= Yii::t('app', 'Subtotal') ?></span>
                    <span>(<?= $textItems ?>):</span>
                    <span style="font-weight: bold;color:#AA0000;font-size: 14pt;">$<?= number_format($sc->getTotal(), 2, '.', '') ?></span>
                </td>
            </tr>
            </tfoot>
        </table>

        <?php
        if (!is_null($controller->getLoggedUser())) {
            echo $this->render('_sc_logged');
        } else {
            if (!isset($login) || is_null($login)) {
                $login = new \app\models\LoginForm();
            }
            echo $this->render('_sc_login', ['model' => $login]);
        }
        ?>

    <?php } else {
        $ids = StoreUtils::getProductIds(8);

        ?>
        <h2><?= Yii::t('app', 'Your Shopping Cart is Empty') ?></h2>
        <h2><?= Yii::t('app', 'We Recommend') ?></h2>
        <div>
            <?= $this->render('//parts/_products', ['ids' => $ids, 'template' => '_product_noajax', 'columns' => 4]) ?>
        </div>

    <?php } ?>


</div>
