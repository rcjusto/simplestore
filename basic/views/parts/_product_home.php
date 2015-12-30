<?php
use app\models\Product;
use yii\widgets\ActiveForm;

/** @var $model Product */

$images = $model->getListImages();
$no_image = '/images/no-image.jpg';
$link = $model->getUrl()

?>
<div class="product_home">
    <div class="product-image">
        <a href="<?= $link ?>"><img src="<?= count($images) > 0 ? $images[0] : $no_image ?>"></a>
    </div>
    <div class="product-name">
        <a href="<?= $link ?>"><?= $model->getName(Yii::$app->language) ?></a>
    </div>
    <div class="product-price">
        $<?= number_format($model->price_custom, 2, '.', '') ?>
    </div>
    <div class="product-buttons">
        <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['shopcart/add-product', 'id' => $model->id]), 'options'=>['class' => 'add-to-cart']]); ?>
        <div class="input-group">
            <?= \yii\bootstrap\Html::dropDownList('quantity', 1, [1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9], ['class' => 'form-control']) ?>
            <span class="input-group-btn">
                <button class="add-to-cart btn btn-default" type="button"><span class=" glyphicon glyphicon-shopping-cart"></span> <?= Yii::t('app','Buy')?></button>
            </span>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>