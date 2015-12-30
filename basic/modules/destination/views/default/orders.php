<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 12/3/2015
 * Time: 7:25 PM
 */
use yii\widgets\ListView;

?>
<h2 style="margin-top: 0;"><?= Yii::t('app','My Orders')?></h2>

<div class="user-orders">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_orders_item',
        'layout' => $this->render('_orders_layout'),
        'itemOptions' => [
            'tag' => false,
        ]
    ]) ?>
</div>