<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
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
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web/images/favicon.png')?>">
    <?php $this->head() ?>
    <link href="<?=Yii::getAlias('@web/css/signin.css')?>" rel="stylesheet">
</head>
<body>
<?php $this->beginBody() ?>

<div style="text-align: center;margin-bottom: 60px;padding-bottom: 20px;;border-bottom: 1px solid #cccccc;background-color: #ffffff;">
    <img src="<?=Yii::getAlias('@web/images/logo.png')?>" style="max-width: 100%">
</div>
<div class="container">
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
