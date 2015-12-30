<?php
use app\models\ItemNameSearch;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel ItemNameSearch */
/* @var $dataSource ActiveDataProvider */

$language = Yii::$app->language;

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

$layout = $this->render('_category_layout');

?>

<div class="catalog-category">

    <div class="row category-title">
        <div class="col-sm-12">
            <h2 style=""><?= Yii::t('app','Search Results For:')?> <span style="font-weight: bold;color: #AA0000"><?= $searchModel->search?></span></h2>
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
