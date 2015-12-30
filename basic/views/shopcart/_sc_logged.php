<?php
/** @var BaseSiteController $controller */

use app\controllers\BaseSiteController;

?>
<div class="row">
    <div class="col-md-6" >
        <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['checkout'])?>"><?= Yii::t('app','Checkout')?></a>
    </div>
</div>
