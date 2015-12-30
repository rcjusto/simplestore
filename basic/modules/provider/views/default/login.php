<?php use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-signin'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<h2 class="form-signin-heading">Please sign in</h2>
<?=$form->errorSummary($model)?>
<label for="inputEmail" class="sr-only">Username</label>
<?= Html::activeTextInput($model, 'username', ['id'=>'inputEmail', 'class'=>'form-control' ,'placeholder'=>'Username', 'required'=>'', 'autofocus'=>'']) ?>
<label for="inputPassword" class="sr-only">Password</label>
<?= Html::activePasswordInput($model, 'password', ['id'=>'inputPassword', 'class'=>'form-control' ,'placeholder'=>'Password', 'required'=>'']) ?>

<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

<?php ActiveForm::end(); ?>
