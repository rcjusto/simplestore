<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductImportForm */

$this->title = Yii::t('app', 'Import Products');
$this->params['breadcrumbs'][] = $this->title;

$message_error =  Yii::$app->session->getFlash('products_error');
$message_info =  Yii::$app->session->getFlash('products_info');

?>
<div class="product-index">

    <div class="row">
        <div class="col-xs-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="product-form">

        <?php if (!is_null($message)) { ?>
            <div style="margin: 20px 0 40px 0"><?=$message?></div>
        <?php } ?>

        <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['import']), 'options' => ['enctype' => 'multipart/form-data']]); ?>


        <?= $form->field($model, 'importFile')->widget(FileInput::classname(), [
            'options' => ['multiple' => false, 'accept' => 'text/csv'],
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
