<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Block */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'url_code')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'active')->dropDownList([0 => 'No', 1 => 'Yes']) ?>
        </div>
    </div>

    <?php foreach (\app\models\Property::getLanguages() as $lang) { ?>
        <h3>Language: <?= $lang ?></h3>
        <div class="row">
            <div class="col-lg-12">
                <label>Title</label>
                <?= Html::textInput("title[$lang]", $model->getTitle($lang), ['class' => 'form-control']) ?>
            </div>
            <div class="col-lg-12">
                <label>Content</label>
                <?php echo TinyMce::widget([
                    'name' => "content[$lang]",
                    'value' => $model->getContent($lang),
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


    <div class="form-group" style="margin-top: 30px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
