<?php
/** @var $zone string */
use app\models\Category;
use yii\bootstrap\Html;

$language = Yii::$app->language;

/** @var Category[] $list */
$list = Category::getChildren();
if (!empty($list)) {
    echo Html::beginTag('div', ['class'=>'departments']);
    echo Html::beginTag('ul');
    foreach($list as $cat) {
        echo Html::beginTag('li');
        echo Html::a($cat->getName($language), $cat->getUrl());
        $children = Category::getChildren($cat->id);
        if (!empty($children)) {
            echo Html::beginTag('ul');
            foreach($children as $subcat) {
                echo Html::beginTag('li');
                echo Html::a($subcat->getName($language), $subcat->getUrl());
                echo Html::endTag('li');
            }
            echo Html::endTag('ul');
        }
        echo Html::endTag('li');
    }
    echo Html::endTag('ul');
    echo Html::endTag('div');
}