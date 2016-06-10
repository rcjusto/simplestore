<?php

/* @var $this yii\web\View */

use app\components\StoreUtils;

$ids = StoreUtils::getProductIds(9);
$this->title = \app\models\Property::getPropertyValue('store_name', '');
?>
<div class="site-index">

    <div>
        <?= $this->render('//parts/_banner', ['zone'=>'home_top'])?>
    </div>

    <div class="row" style="margin-top: 10px;">

        <div class="col-md-3">
            <?= $this->render('//layouts/_column_left');?>
        </div>

        <div class="col-md-6">
            <?= $this->render('//parts/_products', ['ids'=>$ids, 'template'=>'_product_home' ,'columns'=>3])?>
        </div>

        <div class="col-md-3">
            <div class="column_right">
                <div><?=$this->render('//parts/_block', ['id'=>'home_right']); ?></div>
                <?= $this->render('//parts/_banner', ['zone'=>'home_right'])?>
            </div>
        </div>

    </div>

</div>
