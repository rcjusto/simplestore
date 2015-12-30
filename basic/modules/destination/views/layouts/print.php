<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
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
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web/images/favicon.png')?>">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <div style="text-align: center;margin: 0 0 10px 0"><img src="/images/logo.png" style="max-width: 100%"></div>
        <div>
            <?= $content ?>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
<script>
    window.print();
</script>
</body>
</html>
<?php $this->endPage() ?>
