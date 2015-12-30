<?php use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Html;

    $this->title = 'Administration Module';

    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-signin'],
    ]); ?>

    <h2 class="form-signin-heading">Please sign in</h2>
    <?=$form->errorSummary($model)?>
    <label for="inputEmail" class="sr-only">Username</label>
    <?= Html::activeTextInput($model, 'username', ['id'=>'inputEmail', 'class'=>'form-control' ,'placeholder'=>'Username', 'required'=>'', 'autofocus'=>'']) ?>
    <label for="inputPassword" class="sr-only">Password</label>
    <?= Html::activePasswordInput($model, 'password', ['id'=>'inputPassword', 'class'=>'form-control' ,'placeholder'=>'Password', 'required'=>'']) ?>
    <div class="checkbox">
        <label>
            <?= Html::activeCheckbox($model, 'rememberMe') ?>
        </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

    <?php ActiveForm::end(); ?>
