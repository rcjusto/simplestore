<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;
use yii\widgets\Menu;

$languages = [
    'es' => utf8_encode('Español'),
    'en' => utf8_encode('English'),
];

$topLinks = [
    ['label' => Yii::t('app', 'About Us'), 'url' => ['site/page', 'code' => 'quienes-somos']],
    ['label' => Yii::t('app', 'Terms and conditions'), 'url' => ['site/page', 'code' => 'terminos-y-condiciones']],
    ['label' => Yii::t('app', 'Payment methods'), 'url' => ['site/page', 'code' => 'metodos-de-pago']],
    ['label' => Yii::t('app', 'Contact Us'), 'url' => ['site/page', 'code' => 'contactenos']],
];

?>

<div id="top-header" style="margin-bottom: 12px;">
    <div class="container">
        <div class="row">
            <div class="col-md-5" >
                <a id="logo" href="<?= Url::to('/', true) ?>"><img src="<?=Yii::getAlias('@web/images/logo.png')?>"></a>
            </div>
            <div class="col-md-7" id="top-search">
                <div style="text-align: right;display: none">
                    <?= Menu::widget(['items' => $topLinks, 'options' => ['id' => 'top-links']]); ?>
                </div>
                <div style="text-align: right;margin-top: 20px;">
                    <ul id="top-langs">
                        <?php foreach (\app\models\Property::getLanguages() as $lang) { ?>
                            <?php if (Yii::$app->language==$lang) { ?>
                                <li><span class="flag flag-<?= $lang ?> selected"><?= $languages[$lang] ?></span></li>
                            <?php } else { ?>
                                <li><a href="<?= Url::to(['/destination/default/lang', 'lang'=>$lang])?>" class="flag flag-<?= $lang ?>"><?= $languages[$lang] ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
