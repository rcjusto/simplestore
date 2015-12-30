<?php
/** @var $model \app\models\UserShippingAddress */

use yii\helpers\Url;

if (!is_null($model)) {
?>
<div class="row">
    <div class="col-xs-1" style="text-align: right;padding-left: 3px;padding-top: 5px;">
        <span style="font-size: 22pt;color:#337ab7;" class="glyphicon glyphicon-user"></span>
    </div>
    <div class="col-xs-7">
        <div style="font-size: 14pt;margin-bottom: 4px;"><?=$model->shipping_contact?></div>
        <div><span class="glyphicon glyphicon-envelope"></span> <?=$model->shipping_email?></div>
        <div><span class="glyphicon glyphicon-phone-alt"></span> <?=$model->shipping_phone?></div>
    </div>
    <div class="col-xs-4">
        <div style="margin-top: 6px;"><a class="destination_edit" href="<?= Url::to(['user/destination-edit', 'id'=>$model->id])?>"><span class="glyphicon glyphicon-pencil"></span> <?=Yii::t('app','Modify')?></a></div>
        <div style="margin-top: 6px;"><a class="destination_delete" href="<?= Url::to(['user/destination-delete', 'id'=>$model->id])?>"><span class="glyphicon glyphicon-remove"></span> <?=Yii::t('app','Delete')?></a></div>
    </div>
</div>
<?php } else { ?>
    <a class="destination_edit btn btn-primary" href="<?=Url::to(['user/destination-create'])?>"><?=Yii::t('app','Add Destination')?></a>
<?php } ?>
