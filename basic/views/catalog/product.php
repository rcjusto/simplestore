<?php
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model Product */

$images = $model->getDetailImages();
if (empty($images)) {
    $images = $model->getListImages();
}

?>

<div class="catalog-product">


    <div class="row product-title">
        <div class="col-lg-6">
            <div class="product-title"><?= $model->getName(Yii::$app->language) ?></div>
        </div>

        <div class="col-lg-6">

        </div>
    </div>

    <div class="row">
        <?php if (count($images)>0) { ?>
        <div class="col-sm-6">
            <img src="<?= $images[0] ?>" style="max-width: 100%">
        </div>
        <?php } ?>

        <div class="col-sm-5 col-sm-offset-1">
            <div class="product-name"><?= $model->getName(Yii::$app->language) ?></div>
            <div class="product-description"><?= $model->getDescription(Yii::$app->language) ?></div>
            <div class="product-price">$<?= number_format($model->price_custom, 2, '.', '') ?></div>
            <form class="add-to-cart" action="<?= \yii\helpers\Url::to(['shopcart/add-product', 'id' => $model->id]) ?>">
                <div class="input-group input-group-lg">
                    <?= \yii\bootstrap\Html::dropDownList('quantity', 1, [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9], ['class' => 'form-control']) ?>
                    <span class="input-group-btn">
                            <button class="add-to-cart btn btn-primary" type="submit"><span class=" glyphicon glyphicon-shopping-cart"></span> <?= Yii::t('app','Buy')?></button>
                            </span>
                </div>
            </form>
            <div class="product-favorites">

            </div>
        </div>

        <div class="col-sm-12">
            <div class="product-information"><?= $model->getInformation(Yii::$app->language) ?></div>
        </div>


    </div>

</div>
