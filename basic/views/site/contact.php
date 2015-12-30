<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app','Contact Us');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')) { ?>

        <div class="alert alert-success">
            <?= Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.') ?>
        </div>

    <?php } else { ?>

        <p>
            <?= Yii::t('app', 'If you have business inquiries or other questions, please fill out the following form to contact us.') ?>
        </p>

        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'name') ?>
            </div>

            <div class="col-lg-6">
                <?= $form->field($model, 'email') ?>
            </div>

            <div class="col-lg-12">
                <?= $form->field($model, 'subject') ?>
            </div>

            <div class="col-lg-12">
                <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php } ?>

</div>