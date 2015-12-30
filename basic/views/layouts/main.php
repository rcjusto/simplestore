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
    <?= $this->render('//parts/_google_analytics')?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('//layouts/_header')?>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<?= $this->render('//layouts/_footer')?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
