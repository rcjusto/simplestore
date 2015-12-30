<?php

use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelImages app\models\ProductImagesForm */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

$modelImages = new \app\models\ProductImagesForm();

$listImages = $model->getListImages();
$detImages = $model->getDetailImages();

list($usec, $sec) = explode(' ', microtime());
mt_srand( (10000000000 * (float)$usec) ^ (float)$sec );

?>
<style>
    .block-image {position: relative;display: inline-block;margin: 6px;padding: 6px;box-shadow: #dddddd 0 0 4px;}
    .block-image a {display: none;position: absolute;right:0;top:0;padding: 2px 5px;background: #337ab7;color:#ffffff;}
    .block-image:hover a {display: block;}
</style>
<div class="product-form">

    <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['images-add','id'=>$model->id]), 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php foreach($listImages as $img) { ?>
        <div class="block-image" >
            <a href="<?= \yii\helpers\Url::to(['image-delete','id'=>$model->id,'name'=>basename($img)])?>" onclick="return confirm('Delete the image?');"><span class="glyphicon glyphicon-remove"></span></a>
            <img src="<?= $img . '?rnd=' .mt_rand() ?>" style="max-height: 100px;">
        </div>
    <?php } ?>

    <?= $form->field($modelImages, 'listFile')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*'],
    ]); ?>

    <?php foreach($detImages as $img) { ?>

        <div class="block-image" >
            <a href="<?= \yii\helpers\Url::to(['image-delete','id'=>$model->id,'name'=>basename($img)])?>" onclick="return confirm('Delete the image?');"><span class="glyphicon glyphicon-remove"></span></a>
            <img src="<?= $img . '?rnd=' .mt_rand()?>" style="max-height: 100px;">
        </div>
    <?php } ?>
    <?= $form->field($modelImages, 'detailFile')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*'],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
