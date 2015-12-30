<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

$hideFilter = empty($model->code) && empty($model->name) && empty($model->active) && empty($model->category_id) && empty($model->provider_id);

?>

<div class="product-search" style="background-color: #f8f8f8;padding: 1px 20px;margin-bottom: 10px;position: relative;">

    <a href="#" style="position: absolute;right: 15px;top:5px;" onclick="$('.filters').toggle();return false;">Show/Hide Filters</a>
    <div class="filters" style="<?= $hideFilter ? 'display: none;' : ''?>">
    <h3>Filter by</h3>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'code') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'active')->dropDownList([0 => 'Inactive', 1 => 'Active'], ['prompt' => '', 'class' => 'form-control']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-sm-2">
            <label style="display: block;">&nbsp;</label>
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default', 'onclick'=>"$(this).closest('form').find('input,select').each(function(){ $(this).val('') });"]) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
    </div>

</div>
