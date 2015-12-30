<?php
/** @var $pending OrderProducts[] */
use app\models\OrderProducts;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title

?>

<div class="destination-default-index">

    <?php if (!empty($pending)) { ?>
        <h2><?= Yii::t('app', 'You have pending items') ?></h2>

        <div class="row">
            <?php foreach ($pending as $op) { ?>
                <div class="col-lg-4 col-md-6">
                    <div class="clearfix" style="padding: 20px 0; height: 140px;">
                        <div style="float: left;height: 100px;min-width:120px;margin-right:20px;"><?= Html::img($op->product->getListImage(), ['style' => 'max-height:100px;']) ?></div>
                        <div style="margin-bottom: 12px;">
                            <div style="font-size: 13pt;;"><?= $op->description ?></div>
                            <div style="margin-bottom: 10px;"><?= Yii::t('app', '{num} pending', ['num' => $op->quantity - $op->consumed])  ?></div>
                            <div><?= Yii::t('app', 'Order #') . ' ' . $op->order_id ?> &nbsp;&nbsp;&nbsp;&nbsp; <a href="<?= Url::to(['order', 'id' => $op->order_id]) ?>"><?= Yii::t('app', 'See details') ?></a></div>
                            <div><?= date('Y-m-d', strtotime($op->order->created)) ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>



    <?php } else { ?>
        <h2><?= Yii::t('app', 'You have no pending items') ?></h2>
        <p></p>
    <?php } ?>

</div>
