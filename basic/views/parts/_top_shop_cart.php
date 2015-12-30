<?php

use app\controllers\BaseSiteController;

/** @var BaseSiteController $controller */
$controller = Yii::$app->controller;
$sc = $controller->getShoppingCart();
$numProds = $sc->getNumProducts();
$textItems = Yii::t('app', 'Empty');
if ($numProds == 1) $textItems = '1 ' . Yii::t('app', 'item');
else if ($numProds > 1) $textItems = $numProds . ' ' . Yii::t('app', 'items');

$message = isset($_REQUEST['message']) && !empty($_REQUEST['message']) ? $_REQUEST['message'] : '';
$flash = isset($_REQUEST['flash']) && $_REQUEST['flash'];

?>

<div id="top_shop_cart" style="">
    <a href="<?=\yii\helpers\Url::to(['shopcart/index']) ?>">
    <div class="showcart-total">$<?= number_format($sc->getTotal(), 2, '.', '') ?></div>
    <div class="showcart-items"><?= $textItems ?></div>
    </a>
    <div class="showcart-content">
        <?php if (!empty($message)) { ?>
            <div class="showcart-content-message"><?= $message?></div>
        <?php } ?>
        <?php if (count($sc->items) > 0) { ?>
            <div class="shopcart-checkout">
                <a class="btn btn-primary form-control" href="<?= \yii\helpers\Url::to(['shopcart/checkout'])?>"><?=Yii::t('app','Checkout Now')?></a>
            </div>
            <table width="100%">
                <?php foreach ($sc->items as $index => $it) { $itP =$it->getProduct(); ?>
                    <tr>
                        <td><a href="<?=$itP->getUrl()?>"><?= $it->quantity ?> <?= $itP->getName() ?></a></td>
                        <td style="text-align: right"><?= number_format($it->getSubtotal(), 2, '.', '') ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div style="text-align: center"><?= Yii::t('app', 'The shopping cart is empty') ?></div>
        <?php } ?>
    </div>
</div>
<?php if (false && $flash) { ?>
<script>
    $('#topShopCartContainer').addClass('hover');
    setTimeout(function(){$('#topShopCartContainer').removeClass('hover');$('.showcart-content-message').hide();},3000);
</script>
<?php } ?>