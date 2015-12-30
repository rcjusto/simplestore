<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$provider = Yii::$app->session->get('provider', null);
if (!($provider instanceof \app\models\Provider)) $provider = null;
$storeName = \app\models\Property::getPropertyValue('store_name', '');

if (!is_null($provider)) {
    $menu = [
        ['label' => 'Home', 'url' => ['/provider/default']],
        ['label' => 'Products', 'url' => ['/provider/product']],
        ['label' => 'Orders', 'url' => ['/provider/order']],
        ['label' => 'Profile', 'url' => ['/provider/default/profile']],
        [
            'label' => 'Logout (' . $provider->email . ')',
            'url' => ['/provider/default/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
    ];
} else {
    $menu = [
        ['label' => 'Home', 'url' => ['/provider/default/index']],
        ['label' => 'Login', 'url' => ['/provider/default/login']],
    ];
}


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web/images/favicon.png')?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap module-providers">
    <?php


    NavBar::begin([
        'brandLabel' => $storeName . ' - ' . (!is_null($provider) ? $provider->name : Yii::t('app', 'Providers Module')),
        'brandUrl' => ['/site/index'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menu,
    ]);
    NavBar::end();
    ?>

    <div class="container" style="margin-top: 60px;">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('app','Home'),
                'url' => ['/provider/default/index'],
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="copyright-ex">&copy; <?=Yii::t('app','Copyright {year} by {company} All rights Reserved',['year'=>date('Y'), 'company'=>'Aggua Inc.'])?></div>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
