<?php
/* @var $this yii\web\View */
/* @var $data ActiveDataProvider */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>
<h2><?= Yii::t('app','My Favorites')?></h2>

<div class="user-favories">
    <?= ListView::widget([
        'dataProvider' => $data,
        'itemView' => '_order',
        'layout' => "{summary}\n<div class='item-container'>{items}</div>\n{pager}",
        'itemOptions' => [
            'tag' => false,
        ]
    ]) ?>
</div>

<h2><?= Yii::t('app','You also would like')?></h2>
<div>
    <?= $this->render('//parts/_products', ['ids'=>[1,2,3,4], 'template'=>'_product_home'])?>
</div>