<?php

/* @var $this yii\web\View */
/* @var $model Block */


use app\models\Block;
use yii\helpers\Html;

$language = Yii::$app->language;

$this->title = $model->getTitle($language);
?>
<div class="site-page">
    <h1><?= Html::encode($model->getTitle($language)) ?></h1>

    <?= $model->getContent($language) ?>
</div>
