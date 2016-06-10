<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 11/1/2015
 * Time: 11:12 AM
 */
?>
<div class="column_left">
<div class="categories">
    <h4><?=Yii::t('app','Categories')?></h4>
    <?=$this->render('//parts/_categories', []); ?>
</div>
<div><?= $this->render('//parts/_banner', ['zone'=>'home_left'])?></div>
<div class="hidden-sm hidden-xs"><?=$this->render('//parts/_block', ['id'=>'home_left']); ?></div>
</div>