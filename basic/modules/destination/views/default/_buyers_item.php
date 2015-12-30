<?php
/** @var $model \app\models\User */

if (!is_null($model)) {
    ?>
    <div class="row">
        <div class="col-xs-1" style="text-align: right;padding-left: 3px;padding-top: 5px;">
            <span style="font-size: 22pt;color:#337ab7;" class="glyphicon glyphicon-user"></span>
        </div>
        <div class="col-xs-11">
            <div style="font-size: 14pt;margin-bottom: 4px;"><?=$model->getFullname()?></div>
            <div><span class="glyphicon glyphicon-envelope"></span> <?=$model->email?></div>
            <div><span class="glyphicon glyphicon-phone-alt"></span> <?=$model->billing_phone?></div>
        </div>
    </div>
<?php } ?>
