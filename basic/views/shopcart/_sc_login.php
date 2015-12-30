<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<div class="row shopcart-login">
    <div class="col-sm-6">
        <?= Html::tag('h2', Yii::t('app', 'Login to Buy')) ?>

        <?php $form = ActiveForm::begin([
            'action' => \yii\helpers\Url::to(['shopcart/login']),
            'method' => 'post',
            'id' => 'formLoginEx',
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['class' => 'input-username form-control', 'placeholder' => Yii::t('app', 'Email address')]) ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'input-password form-control', 'placeholder' => Yii::t('app', 'Password')]) ?>

        <div class="form-group row">
            <div class="col-lg-6">
                <button type="submit" class="btn btn-primary btn-lg full-width"><?=Yii::t('app','Submit')?></button>
            </div>
            <div class="col-lg-6" style="text-align: center">
                <a data-toggle="modal" data-target="#myPwdModal" href="#" style="display:inline-block;margin:10px 0"><?= Yii::t('app', 'Forgot My Password') ?></a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::tag('h2', Yii::t('app', 'New in our Store?')) ?>
        <?= Html::a(Yii::t('app', 'Register to Buy'), ['site/register', 'back'=>Url::to(['shopcart/checkout'])], ['class' => 'btn btn-lg btn-primary full-width']) ?>
    </div>
</div>


<div>


</div>

<!-- Modal -->
<div class="modal fade" id="myPwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app','Forgot My Password')?></h4>
            </div>
            <div class="modal-body">

                <div id="forgotPasswordMessage"></div>
                <div id="forgotPasswordLoading" style="display: none"><?= Yii::t('app','Checking your username...')?></div>
                <div id="forgotPasswordForm">
                    <p><?= Yii::t('app', 'Enter your email address in the box below and click the button "Send me the password". We will send a link to reset your password.') ?></p>
                    <?php ActiveForm::begin([
                        'action' => \yii\helpers\Url::to(['shopcart/forgot-password']),
                        'id' => 'formForgotPassword',
                    ]); ?>
                    <?= Html::textInput('username', '', ['class' => 'input-username form-control input-lg', 'placeholder' => Yii::t('app', 'Email address')]) ?>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="sc-forgot-password-cancel" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                <button type="button" id="sc-forgot-password" class="btn btn-primary"><?= Yii::t('app', 'Send me the password') ?></button>
            </div>
        </div>
    </div>
</div>

