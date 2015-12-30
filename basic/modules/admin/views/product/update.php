<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Product',
]) . ' ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$showImages = ( isset($panel) && $panel=='images');

?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="<?= $showImages ? '' : 'active' ?>"><a href="#product" aria-controls="product" role="tab" data-toggle="tab">Details</a></li>
        <li role="presentation" class="<?= $showImages ? 'active' : '' ?>"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" style="margin-top: 30px;">
        <div role="tabpanel" class="tab-pane <?= $showImages ? '' : 'active' ?>" id="product">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <div role="tabpanel" class="tab-pane <?= $showImages ? 'active' : '' ?>" id="images">
            <?= $this->render('_images', [
                'model' => $model,
            ]) ?>
        </div>
    </div>


</div>
