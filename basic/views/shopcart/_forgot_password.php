<?php

use app\models\User;
use yii\bootstrap\Html;

/** @var $model User */
if (!is_null($model)) {

    echo Html::beginTag('div', ['class'=>'alert alert-success']);
    echo Html::tag('p', Yii::t('app','An email with the information you requested was successfully sent to {email}', ['email'=>$model->email]));
    echo Html::endTag('div');

} else {

    echo Html::beginTag('div', ['class'=>'alert alert-danger']);
    echo Html::tag('p', Yii::t('app','The email address you provided could not be found'));
    echo Html::a(Yii::t('app','Please, try again ...'), '#', ['class'=>'']);
    echo Html::endTag('div');

}