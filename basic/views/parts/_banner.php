<?php
/** @var $zone string */
use app\models\Banner;

/** @var Banner[] $list */
$list = Banner::find()->where(['zone'=>$zone, 'active'=>1])->orderBy(['position'=>SORT_ASC])->all();
if (count($list)>1) {
?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php foreach($list as $index => $banner) { ?>
        <li data-target="#carousel-example-generic" data-slide-to="<?=$index?>" class="<?= $index==0 ? 'active' : ''?>"></li>
        <?php } ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php foreach($list as $index => $banner) { ?>
        <div class="item <?= $index==0 ? 'active' : ''?>">
            <a href="<?=$banner->link?>" target="<?= $banner->getTarget()?>"><img src="<?= $banner->getImage()?>"></a>
        </div>
        <?php } ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<?php
} else if (count($list)==1) {
    $banner = $list[0];
?>
    <a href="<?=$banner->link?>" target="<?= $banner->getTarget()?>">
        <img src="<?=$banner->getImage()?>" class="img-responsive" >
    </a>
<?php } ?>