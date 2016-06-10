<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-lg-2">
            <?= $form->field($model, 'zone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'active')->dropDownList([0 => 'No', 1 => 'Yes']) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'position')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'target')->dropDownList(\app\models\Banner::$targets) ?>
        </div>
    </div>

    <?php foreach (\app\models\Property::getLanguages() as $lang) { ?>
        <div class="row">
            <div class="col-lg-6">
                <label>Image <?= $lang ?></label>
                <?= FileInput::widget([
                    'name' => 'image_' . $lang,
                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        'showUpload' => false
                    ]
                ]); ?>

            </div>
            <div class="col-lg-6">
                <?php if (!$model->isNewRecord) {
                    $img = $model->getImage($lang); ?>
                    <?= !empty($img) ? Html::img($img, ['style' => 'max-width:100%;']) : '' ?>
                <?php } ?>
                <span>&nbsp;</span>
            </div>
        </div>
    <?php } ?>

    <div class="form-group" style="margin-top: 20px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
