<?php
/** @var $model Order */
use app\models\Order;
use yii\helpers\Url;

?>
<tr>
    <td><?=$model->id?></td>
    <td><?=date('Y-m-d',strtotime($model->created))?></td>
    <td><?=$model->getBuyerName()?></td>
    <td style="text-align: center"><?=$model->getTotalProducts()?></td>
    <td style="text-align: center"><?=$model->getTotalConsumed()?></td>
    <td style="text-align: center"><?=$model->getTotalPending()?></td>
    <td style="text-align: center"><a href="<?=Url::to(['/destination/default/order', 'id'=>$model->id])?>"><span class="glyphicon glyphicon-eye-open"></span> <?=Yii::t('app','Details')?></a></td>
</tr>
