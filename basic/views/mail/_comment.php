<?php
/** @var $model CommentForm */

use app\models\CommentForm;

echo $this->render('_top');
?>

<h1>New Comment</h1>
<p>From: <?=$model->name?> (<?=$model->email?>)</p>
<p><?=$model->body?></p>

<?php echo $this->render('_bottom');
