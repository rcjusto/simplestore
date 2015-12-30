<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
    <link href="<?=Yii::getAlias('@web/css/signin.css')?>" rel="stylesheet">
</head>
<body>
<?php $this->beginBody() ?>

<div style="text-align: center;margin-bottom: 60px;padding-bottom: 20px;;border-bottom: 1px solid #cccccc;background-color: #ffffff;">
    <img src="<?=Yii::getAlias('@web/images/logo.png')?>" style="max-width: 100%">
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-7" style="text-align: center">
            <img src="<?=Yii::getAlias('@web/images/administrators.png')?>" style="max-width: 100%">
        </div>
        <div class="col-sm-5">
            <?= $content ?>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
