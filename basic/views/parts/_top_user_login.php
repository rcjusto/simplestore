<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/29/2015
 * Time: 4:49 PM
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$model = new \app\models\LoginForm();

?>
<div class="container">
    <div class="row">
        <div class="col-lg-2 hidden-md hidden-sm hidden-xs" style="text-align: right;line-height: 46px;"><span class="ya-registrado"><?= Yii::t('app','Registered?')?></span></div>
        <?php $form = ActiveForm::begin([
            'action' => \yii\helpers\Url::to(['site/login']),
            'method' => 'post',
            'id' => 'formLogin',
        ]); ?>
        <div class="col-lg-3 has-field">
            <div style="height: 8px;"></div>
            <?= Html::activeTextInput($model, 'username', ['class' => 'input-username form-control input-sm', 'placeholder' => Yii::t('app', 'Email address')]) ?>
        </div>
        <div class="col-lg-2 has-field">
            <div style="height: 8px;"></div>
            <?= Html::activePasswordInput($model, 'password', ['class' => 'input-password form-control input-sm', 'placeholder' => Yii::t('app', 'Password')]) ?>
        </div>
        <div class="col-lg-1 has-field">
            <div style="height: 8px;"></div>
            <input type="submit" value="<?=Yii::t('app','Submit')?>" class="btn btn-primary btn-sm input-sm form-control">
            <div style="height: 8px;"></div>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="col-lg-2 col-xs-6" >
            <div class="with-padding">
                <a href="<?= \yii\helpers\Url::to(['site/register']) ?>">
                    <span class="nuevo-en"><?=Yii::t('app','New in') . ' ' . Yii::$app->name?>?</span>
                    <span class="registrarse"><?=Yii::t('app','REGISTER') ?></span>
                </a>
            </div>
        </div>
        <div class="col-lg-2 col-xs-6" style="text-align: right">
            <div id="topShopCartContainer" data-url="<?= \yii\helpers\Url::to(['shopcart/cart-reload']) ?>"><?= $this->render('_top_shop_cart') ?></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loginErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app','Login Error')?></h4>
            </div>
            <div class="modal-body">
                <?= Yii::t('app','Invalid Username or Password')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app','Close')?></button>
            </div>
        </div>
    </div>
</div>