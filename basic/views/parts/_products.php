<?php
/** @var $ids [] */
/** @var $template string */
/** @var Product[] $list */

use app\models\Product;

$list = [];
if (isset($ids) && is_array($ids)) {
    foreach ($ids as $id) {
        $p = Product::findOne($id);
        if (!is_null($p)) $list[] = $p;
    }
}
$cellClass = 'col-xs-6';
if (isset($columns)) {
    if ($columns == 3) $cellClass = 'col-sm-4 col-xs-6';
    if ($columns == 4) $cellClass = 'col-sm-3 col-xs-6';
}
?>
<div class="row">
    <?php foreach ($list as $model) { ?>
        <div class="<?= $cellClass?>">
            <?= $this->render($template, ['model'=>$model])?>
        </div>
    <?php } ?>
</div>
