<?php
/** @var $model ContactForm */

use app\models\ContactForm;

echo $this->render('_top');
?>

<h1>New Contact Message</h1>
<h3><?=$model->subject?></h3>
<p>From: <?=$model->name?> (<?=$model->email?>)</p>
<p><?=$model->body?></p>

<?php echo $this->render('_bottom');
