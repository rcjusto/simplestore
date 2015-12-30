<?php use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$loginError = Yii::$app->session->getFlash('login-error', '');
$passwordError = Yii::$app->session->getFlash('password-error', '');
$generalInfo = Yii::$app->session->getFlash('general-info', '');
$showPassword = isset($show) && $show=='password';
?>

<div id="form-login" style="<?= $showPassword ? 'display:none;' : ''?>">
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'action' => ['/destination/default/login'],
        'options' => ['class' => 'form-signin'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <?= !empty($generalInfo) ? Html::tag('div', $generalInfo, ['class' => 'alert alert-success']) : ''?>

    <h2 class="form-signin-heading"><?= Yii::t('app','Welcome')?></h2>
    <p><?= Yii::t('app','Enter your credentials to access your orders.')?></p>
    <?= !empty($loginError) ? Html::tag('div', $loginError, ['class' => 'alert alert-danger']) : ''?>
    <label for="inputEmail" class="sr-only"><?=Yii::t('app','Email address')?></label>
    <?= Html::activeTextInput($model, 'username', ['id' => 'inputEmail', 'class' => 'form-control', 'placeholder' => Yii::t('app','Email address'), 'required' => '', 'autofocus' => '']) ?>
    <label for="inputPassword" class="sr-only"><?=Yii::t('app','Password')?></label>
    <?= Html::activePasswordInput($model, 'password', ['id' => 'inputPassword', 'class' => 'form-control', 'placeholder' => Yii::t('app','Password'), 'required' => '']) ?>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?=Yii::t('app','Sign In')?></button>
    <div style="margin: 10px;">
        <a href="#" onclick="$('#form-password').show();$('#form-login').hide();return false"><?=Yii::t('app','Forgot My Password')?></a>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div id="form-password" style="<?= $showPassword ? '' : 'display:none;'?>">
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-password',
        'action' => ['/destination/default/forgot-password'],
        'options' => ['class' => 'form-signin'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <h2 class="form-signin-heading"><?= Yii::t('app','Forgot My Password')?></h2>
    <p><?= Yii::t('app','Enter your email address to send you an email with your credentials.')?></p>
    <?= !empty($passwordError) ? Html::tag('div', $passwordError, ['class' => 'alert alert-danger']) : ''?>
    <label for="inputEmail" class="sr-only"><?=Yii::t('app','Email address')?></label>
    <?= Html::textInput('username', '', ['id' => 'inputEmail', 'class' => 'form-control', 'placeholder' => Yii::t('app','Email address'), 'required' => '']) ?>
    <div style="margin-top: 8px;text-align: center">
        <button class="btn btn-lg btn-primary " type="submit" style="width: 49%;"><?=Yii::t('app','Send')?></button>
        <button class="btn btn-lg btn-default " type="button" style="width: 49%;" onclick="$('#form-password').hide();$('#form-login').show();"><?=Yii::t('app','Cancel')?></button>
    </div>
    <?php ActiveForm::end(); ?>
</div>