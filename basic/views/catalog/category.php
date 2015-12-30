<?php
use app\models\Category;
use app\models\ItemCategorySearch;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel ItemCategorySearch */
/* @var $dataSource ActiveDataProvider */

$category = $searchModel->getCategory();
$language = Yii::$app->language;
$description = $category->getContent($language);


$pageOptions = [
    12 => '12 per page',
    24 => '24 per page',
    48 => '48 per page',
    0 => 'All items',
];

$sortOptions = [
    'id:desc' => 'New',
    'price_custom:asc' => 'Price',
    'url_code:asc' => 'Name',
];


$breadcrumbs = [];
foreach($category->getParents() as $parent) {
    $breadcrumbs[$parent->getName($language)] = $parent->getUrl();
}
$layout = $this->render('_category_layout');

?>

<div class="catalog-category">

    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['site/index'])?>"><?=Yii::t('app','Home')?></a></li>
        <?php foreach($breadcrumbs as $it => $url) { ?>
            <li><a href="<?=$url?>"><?=$it?></a></li>
        <?php } ?>
        <li class="active"><?= $category->getName($language) ?></li>
    </ol>

    <div class="row category-title">
        <div class="col-sm-12">
            <h2 style="margin-top: 0;"><?= $category->getName($language) ?></h2>
            <?= !empty($description) ? Html::tag('div', $description, ['style'=>'margin-bottom:30px;']) : '' ?>
        </div>
    </div>

    <div class="">
        <?php \yii\widgets\Pjax::begin(); ?>
        <?= ListView::widget([
            'dataProvider' => $dataSource,
            'itemView' => '_product',
            'layout' => $layout,
            'itemOptions' => [
                'tag' => false,
            ]
        ]) ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>

</div>
