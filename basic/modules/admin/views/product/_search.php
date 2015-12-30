<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

$categories = ArrayHelper::map(\app\models\Category::find()->orderBy(['url_code' => SORT_ASC])->all(), 'id', 'name');
$providers = ArrayHelper::map(\app\models\Provider::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');

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
        <div class="col-sm-2">
            <?= $form->field($model, 'code') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'active')->dropDownList([0 => 'Inactive', 1 => 'Active'], ['prompt' => '', 'class' => 'form-control']) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '', 'class' => 'form-control']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'provider_id')->dropDownList($providers, ['prompt' => '', 'class' => 'form-control']) ?>
        </div>
        <div class="col-sm-1">
            <label>&nbsp;</label>
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
    </div>

</div>
