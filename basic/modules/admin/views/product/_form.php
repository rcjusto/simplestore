<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

$categories = ArrayHelper::map(\app\models\Category::find()->orderBy(['url_code' => SORT_ASC])->all(), 'id', 'name');
$providers = ArrayHelper::map(\app\models\Provider::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-lg-2">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'type')->dropDownList(\app\models\Product::getProductTypes()) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'category_id')->dropDownList($categories) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'provider_id')->dropDownList($providers) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'shipping')->dropDownList([0=>'No',1=>'Yes']) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'active')->dropDownList([0=>'No',1=>'Yes']) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'stock')->textInput() ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'price_custom')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'featured')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'notify')->textInput(['maxlength' => true]) ?>
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
                <?php echo TinyMce::widget([
                    'name' => "description[$lang]",
                    'value' => $model->getDescription($lang),
                    'options' => ['rows' => 12],
                    'language' => 'es',
                    'clientOptions' => [
                        'content_css' => '/css/bootstrap.min.css,/css/site.css',
                        'plugins' => [
                            "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste",
                            "textcolor jbimages"
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | forecolor"
                    ]
                ]) ?>
            </div>
            <div class="col-lg-12">
                <label>Aditional Information</label>
                <?php echo TinyMce::widget([
                    'name' => "information[$lang]",
                    'value' => $model->getInformation($lang),
                    'options' => ['rows' => 12],
                    'language' => 'es',
                    'clientOptions' => [
                        'content_css' => '/css/bootstrap.min.css,/css/site.css',
                        'plugins' => [
                            "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste",
                            "textcolor jbimages"
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | forecolor"
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <div class="form-group" style="margin-top: 20px">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
