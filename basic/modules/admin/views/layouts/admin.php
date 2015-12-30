<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$storeName = \app\models\Property::getPropertyValue('store_name', '');

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web/images/favicon.png') ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap module-admin">
    <?php
    NavBar::begin([
        'brandLabel' => $storeName . ' - Administration Module',
        'brandUrl' => ['/site/index'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/admin/default']],
            ['label' => 'Catalog', 'items' => [
                ['label' => 'Products', 'url' => ['/admin/product']],
                ['label' => 'Categories', 'url' => ['/admin/category']],
                ['label' => 'Providers', 'url' => ['/admin/provider']],
            ]],
            ['label' => 'Sales', 'items' => [
                ['label' => 'Orders', 'url' => ['/admin/order']],
                ['label' => 'Users', 'url' => ['/admin/user']],
            ]],
            ['label' => 'Tools', 'items' => [
                ['label' => 'Mails', 'url' => ['/admin/mail']],
                ['label' => 'Properties', 'url' => ['/admin/default/properties']],
            ]],
            ['label' => 'CMS', 'items' => [
                ['label' => 'Blocks', 'url' => ['/admin/block']],
                ['label' => 'Banners', 'url' => ['/admin/banner']],
            ]],
            [
                'label' => 'Logout',
                'url' => ['/admin/default/logout'],
                'linkOptions' => ['data-method' => 'post']
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container" style="margin-top: 60px;">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('app','Home'),
                'url' => ['/admin/default/index'],
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="copyright-ex">&copy; <?= Yii::t('app', 'Copyright {year} by {company} All rights Reserved', ['year' => date('Y'), 'company' => 'Aggua Inc.']) ?></div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
