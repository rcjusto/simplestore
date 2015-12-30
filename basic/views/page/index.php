<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div>
        <?= $this->render('//parts/_banner', ['zone'=>'home_top'])?>
    </div>

    <div class="row" style="margin-top: 10px;">

        <div class="col-lg-3 hidden-md hidden-sm hidden-xs">
            <?= $this->render('//parts/_block', ['id'=>'home_left'])?>
        </div>

        <div class="col-lg-9">
            <?= $this->render('//parts/_products', ['ids'=>[1,2,3,4,5], 'template'=>'_product_home'])?>
        </div>

    </div>

</div>
