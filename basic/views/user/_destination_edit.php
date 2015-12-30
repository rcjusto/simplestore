<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

if ($id>0) {
    $title = 'Modify Destination';
    $action = ['user/destination-edit','id'=>$id];
    $urlCancel = Url::to(['user/destination','id'=>$id]);
} else {
    $title = 'Add New Destination';
    $action = ['user/destination-create'];
    $urlCancel = Url::to(['user/destination','id'=>0]);
}

?>


<?php $form = ActiveForm::begin(['layout' => 'horizontal', 'action' => $action]); ?>

    <h4><?= Yii::t('app', $title) ?></h4>
    <?= $form->errorSummary([$model])?>
    <div class="row" style="margin-bottom: 6px;">
        <div class="col-xs-5" style="text-align: right"><?=Yii::t('app','Name')?></div>
        <div class="col-xs-7"><?= Html::activeTextInput($model, 'shipping_contact',['class'=>'form-control']) ?></div>
    </div>
    <div class="row" style="margin-bottom: 6px;">
        <div class="col-xs-5" style="text-align: right"><?=Yii::t('app','Email address')?></div>
        <div class="col-xs-7"><?= Html::activeTextInput($model, 'shipping_email',['class'=>'form-control']) ?></div>
    </div>
    <div class="row" style="margin-bottom: 6px;">
        <div class="col-xs-5" style="text-align: right"><?=Yii::t('app','Phone number')?></div>
        <div class="col-xs-7"><?= Html::activeTextInput($model, 'shipping_phone',['class'=>'form-control']) ?></div>
    </div>
    <div class="row">
        <div class="col-xs-5">&nbsp;</div>
        <div class="col-xs-7">
            <a id="btnDestinationSave" href="#" class="btn btn-primary"><?=Yii::t('app', 'Save')?></a>
            <a id="btnDestinationCancel" href="<?= $urlCancel?>" class="btn btn-primary"><?=Yii::t('app', 'Cancel')?></a>
        </div>
    </div>

<?php ActiveForm::end(); ?>