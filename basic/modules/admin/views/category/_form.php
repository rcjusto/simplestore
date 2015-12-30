<?php

use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */

$id = !empty(($model->id)) ? $model->id : 0;
$templates = ['normal'=>'Normal'];

/** @var Category[] $category_list */
$category_list = Category::find()->where('id<>:id',[':id'=>$id])->orderBy(['url_code' => SORT_ASC])->all();
$categories = ['' => 'None'];
foreach($category_list as $cat) {
    $categories[$cat->id] = $cat->getName();
}


?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">

        <div class="col-lg-3">
            <?= $form->field($model, 'template')->dropDownList($templates) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'parent')->dropDownList($categories) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'show_in_menu')->dropDownList([0=>'No',1=>'Yes']) ?>
        </div>
    </div>

    <?php foreach(\app\models\Property::getLanguages() as $lang) { ?>
        <h3>Language: <?=$lang?></h3>
        <div class="row">
            <div class="col-lg-12">
                <label>Name</label>
                <?= Html::textInput("name[$lang]", $model->getName($lang), ['class'=>'form-control'])?>
            </div>
            <div class="col-lg-12">
                <label>Description</label>
                <?= Html::textarea("content[$lang]", $model->getContent($lang), ['class'=>'form-control'])?>
            </div>
        </div>
    <?php } ?>

    <div class="form-group" style="margin-top: 20px">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
