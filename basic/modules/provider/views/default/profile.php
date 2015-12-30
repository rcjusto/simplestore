<?php
/** @var $provider \app\models\Provider */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$passwordInfo = Yii::$app->session->getFlash('password-info', '');
$passwordError = Yii::$app->session->getFlash('password-error', '');

?>

<div class="row">
    <div class="col-sm-5">
        <h2><?= $provider->name ?></h2>
        <table class="table">
            <tr>
                <th>Email</th>
                <td><?= $provider->email ?></td>
            </tr>
            <tr>
                <th>Instructions</th>
                <td><?= $provider->destination_instructions ?></td>
            </tr>
        </table>


    </div>
    <div class="col-sm-6 col-sm-offset-1">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
        <div style="margin-top: 50px;">
            <?= !empty($passwordInfo) ? Html::tag('div',$passwordInfo,['class'=>'alert alert-success']) : ''?>
            <h4><?= Yii::t('app', 'Change Your Password') ?></h4>
            <?= !empty($passwordError) ? Html::tag('div',$passwordError,['class'=>'alert alert-danger']) : ''?>
            <div class="row">
                <div class="col-xs-6">
                    <?= Html::passwordInput('password', '', ['class' => 'form-control', 'placeholder'=>Yii::t('app','Enter new password')]) ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton(Yii::t('app', 'Update Password'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
