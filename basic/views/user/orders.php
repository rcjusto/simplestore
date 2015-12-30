<?php
/* @var $this yii\web\View */
/* @var $data ActiveDataProvider */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>
<h1><?= Yii::t('app','My Orders')?></h1>

<div class="user-orders">
    <?= ListView::widget([
        'dataProvider' => $data,
        'itemView' => '_order',
        'layout' => "{summary}\n<div class='item-container'>{items}</div>\n{pager}",
        'itemOptions' => [
            'tag' => false,
        ]
    ]) ?>
</div>