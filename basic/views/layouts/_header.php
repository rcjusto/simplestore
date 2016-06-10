<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;
use yii\widgets\Menu;

$languages = [
    'es' => utf8_encode('Espa&ntilde;ol'),
    'en' => utf8_encode('English'),
];

$topLinks = [
    ['label' => Yii::t('app', 'About Us'), 'url' => ['site/page', 'code' => 'quienes-somos']],
    ['label' => Yii::t('app', 'Terms and conditions'), 'url' => ['site/page', 'code' => 'terminos-y-condiciones']],
    ['label' => Yii::t('app', 'Payment methods'), 'url' => ['site/page', 'code' => 'metodos-de-pago']],
    ['label' => Yii::t('app', 'Contact Us'), 'url' => ['site/contact']],
    ['label' => Yii::t('app', 'How to buy'), 'url' => ['site/page', 'code' => 'how-to-buy']],
];

?>
<div id="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-10" style="text-align: center">
                <?= Menu::widget(['items' => $topLinks, 'options' => ['id' => 'top-links']]); ?>
            </div>
            <div class="col-md-2" style="text-align: center">
                <ul id="top-langs">
                    <?php foreach (\app\models\Property::getLanguages() as $lang) { ?>
                        <?php if (Yii::$app->language==$lang) { ?>
                            <li><span class="flag flag-<?= $lang ?> selected"><?= $languages[$lang] ?></span></li>
                        <?php } else { ?>
                            <li><a href="<?= Url::to(['site/lang', 'lang'=>$lang])?>" class="flag flag-<?= $lang ?>"><?= $languages[$lang] ?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>
</div>

<div id="top-header">
    <div class="container">
        <div class="row">
            <div class="col-md-5" >
                <a id="logo" href="<?= Url::to('/', true) ?>"><img src="/images/logo.png"></a>
            </div>
            <div class="col-md-7" id="top-search">
                <form method="get" action="<?= Url::to(['catalog/search'])?>">
                <div style="margin: 5%;">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" value="<?= isset($_REQUEST['search']) ? $_REQUEST['search'] : ''?>" class="form-control" placeholder="<?=Yii::t('app','Search for')?>...">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="top-user">
    <?= $this->render(Yii::$app->user->isGuest ? '//parts/_top_user_login' : '//parts/_top_user_menu') ?>
</div>