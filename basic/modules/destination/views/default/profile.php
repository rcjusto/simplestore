<?php
/** @var $shipping \app\models\OrderShipping */
/** @var $destination \app\models\DestinationAccount */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$passwordInfo = Yii::$app->session->getFlash('password-info', '');
$passwordError = Yii::$app->session->getFlash('password-error', '');

?>

<div class="row">
    <div class="col-sm-6">
        <h2><?= Yii::t('app', 'My Profile') ?></h2>

        <div style="font-size: 16pt;"><?= $shipping->shipping_contact ?></div>
        <div><?= $destination->email ?></div>

        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
        <div style="max-width: 260px;margin-top: 50px;">
            <?= !empty($passwordInfo) ? Html::tag('div',$passwordInfo,['class'=>'alert alert-success']) : ''?>
            <h4><?= Yii::t('app', 'Change Your Password') ?></h4>
            <?= !empty($passwordError) ? Html::tag('div',$passwordError,['class'=>'alert alert-danger']) : ''?>
            <?= Html::passwordInput('password', '', ['class' => 'form-control', 'placeholder'=>Yii::t('app','Enter new password')]) ?>
            <div style="margin: 10px 0;">
            <?= Html::submitButton(Yii::t('app', 'Update Password'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
    <div class="col-sm-6">
        <h3><?= Yii::t('app', 'My Buyers') ?></h3>

        <div>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_buyers_item',
                'layout' => $this->render('_buyers_layout'),
                'itemOptions' => [
                    'tag' => false,
                ]
            ]) ?>
        </div>
    </div>
</div>
