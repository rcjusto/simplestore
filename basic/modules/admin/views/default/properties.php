<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 12/4/2015
 * Time: 5:02 PM
 */
use app\models\Property;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<style>
    label {
        margin-top: 12px;
        margin-bottom: 0;
    }
</style>
<h1>Configuration</h1>

<?php $form = ActiveForm::begin([]); ?>

<h3>Search Engine Optimization</h3>
<div>
    <label>Store Name</label>
    <?= Html::textInput('properties[store_name]', Property::getPropertyValue('store_name', ''), ['class' => 'form-control']) ?>
    <label>Default Title</label>
    <?= Html::textInput('properties[store_title]', Property::getPropertyValue('store_title', ''), ['class' => 'form-control']) ?>
    <label>META Description</label>
    <?= Html::textarea('properties[store_description]', Property::getPropertyValue('store_description', ''), ['class' => 'form-control']) ?>
    <label>META Keywords</label>
    <?= Html::textarea('properties[store_keywords]', Property::getPropertyValue('store_keywords', ''), ['class' => 'form-control']) ?>
    <label>Google Analytics ID</label>
    <?= Html::textInput('properties[google_analytics_id]', Property::getPropertyValue('google_analytics_id', ''), ['class' => 'form-control']) ?>
</div>

<h3>Authorize.net</h3>
<div>
    <label>Access Key</label>
    <?= Html::textInput('properties[authorize_key]', Property::getPropertyValue('authorize_key', ''), ['class' => 'form-control']) ?>
    <label>Access Login</label>
    <?= Html::textInput('properties[authorize_login]', Property::getPropertyValue('authorize_login', ''), ['class' => 'form-control']) ?>
    <label>Status</label>
    <?= Html::dropDownList('properties[authorize_live]', Property::getPropertyValue('authorize_live', ''), ['no' => 'Test Mode', 'yes' => 'Live Mode'],  ['class' => 'form-control']) ?>
</div>

<h3>Social Sites</h3>
<div>
    <label>Facebook</label>
    <?= Html::textInput('properties[facebook_link]', Property::getPropertyValue('facebook_link', ''), ['class' => 'form-control']) ?>
    <label>Twitter</label>
    <?= Html::textInput('properties[twitter_link]', Property::getPropertyValue('twitter_link', ''), ['class' => 'form-control']) ?>
</div>

<div style="margin-top: 10px;">
    <button class="btn btn-lg btn-primary " type="submit">Save</button>
</div>

<?php ActiveForm::end(); ?>
