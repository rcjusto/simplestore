<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$destination = Yii::$app->session->get('destination', null);
if (!($destination instanceof \app\models\DestinationAccount)) $destination = null;
$storeName = \app\models\Property::getPropertyValue('store_name', '');

$menu = [];
if (!is_null($destination)) {
    $menu = [
        ['label' => Yii::t('app','Home'), 'url' => ['/destination/default/index']],
        ['label' => Yii::t('app','Orders'), 'url' => ['/destination/default/orders']],
        ['label' => Yii::t('app','My Profile'), 'url' => ['/destination/default/profile']],
        [
            'label' => Yii::t('app','Logout'),
            'url' => ['/destination/default/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
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

<div id="destination" class="wrap">
<?= $this->render('_header')?>

    <?php
    NavBar::begin([
        'brandLabel' => (!is_null($destination) ? $destination->email : Yii::t('app', 'Destination Module')),
        'options' => [
            'class' => 'navbar-inverse ',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menu,
    ]);
    NavBar::end();
    ?>

    <div class="container" >
        <?= Breadcrumbs::widget([
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
