<?php
/* @var $this \yii\web\View */

use app\models\User;
use yii\widgets\Menu;

/** @var User $user */
$user = Yii::$app->controller->getLoggedUser();

$userLinks = [
    ['label' => Yii::t('app','Orders'), 'url' => ['user/orders','id'=>'1']],
    ['label' => Yii::t('app','Profile'), 'url' => ['user/profile','id'=>'1']],
   // ['label' => Yii::t('app','Favorites'), 'url' => ['user/favorites','id'=>'1']],
    ['label' => Yii::t('app','Logout'), 'url' => ['user/logout'], 'linkOptions' => ['data-method' => 'post']],
];

?>
<div class="container">
    <div class="row">
        <div class="col-lg-4"><span class="logged_user_name"><?= !is_null($user) ? $user->name : ''?></span></div>
        <div class="col-lg-6 col-xs-7" style="text-align: left">
            <?= Menu::widget(['items' => $userLinks, 'options' => ['id'=>'user-links'] ]);?>
        </div>
        <div class="col-lg-2 col-xs-5">
            <div id="topShopCartContainer" data-url="<?= \yii\helpers\Url::to(['shopcart/cart-reload'])?>"><?=$this->render('_top_shop_cart')?></div>
        </div>
    </div>
</div>
