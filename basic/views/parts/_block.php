<?php
use app\models\Block;

/** @var $id string */
/** @var Block $block */

$block = Block::findOne($id);
if (!is_null($block)) { ?>
<div class="block_<?=$id?>">
    <?= $block->getContent(Yii::$app->language)?>
</div>
<?php }