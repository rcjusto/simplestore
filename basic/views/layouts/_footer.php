<?php

use app\models\Property;
use yii\bootstrap\Html;
use yii\helpers\Url;

$list = [
    'col1' => [
        ['label' => Yii::t('app', 'About Us'), 'url' => ['site/page', 'code' => 'quienes-somos']],
        ['label' => Yii::t('app', 'Payment methods'), 'url' => ['site/page', 'code' => 'metodos-de-pago']],
        ['label' => Yii::t('app', 'Contact Us'), 'url' => ['site/contact']],
    ],
    'col2' => [
        ['label' => Yii::t('app', 'My Profile'), 'url' => ['user/profile']],
        ['label' => Yii::t('app', 'My Orders'), 'url' => ['user/orders']],
        ['label' => Yii::t('app', 'Shopping Cart'), 'url' => ['shopcart/index']],
    ],
    'col3' => [
        ['label' => Yii::t('app', 'Terms and conditions'), 'url' => ['site/page', 'code' => 'terminos-y-condiciones']],
        ['label' => Yii::t('app', 'How to buy'), 'url' => ['site/page', 'code' => 'how-to-buy']],
        ['label' => Yii::t('app', 'Send your comments'), 'url' => ['site/comment']],
    ],
];

$facebook = Property::getPropertyValue('facebook_link', '#');
$twitter = Property::getPropertyValue('twitter_link', '');
$showSocial = strlen($facebook . $twitter) > 0;
?>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-xs-4" style="margin-bottom: 20px;text-align: center">
                <!-- (c) 2005, 2015. Authorize.Net is a registered trademark of CyberSource Corporation -->
                <div class="AuthorizeNetSeal" style="display: inline-block!important;">
                    <script type="text/javascript" language="javascript">var ANS_customer_id = "9822f86e-447f-40f5-a96a-3386ed5544f8";</script>
                    <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js"></script>
                    <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Online Payment Service</a></div>
            </div>
            <div class="col-md-3 col-xs-8">
                <div class="footer_left" style="text-align: center">
                    <?php if ($showSocial) { ?>
                        <div class="social-links">
                            <span><?= Yii::t('app', 'Share in') ?></span>
                            <?= !empty($facebook) ? Html::a(Html::img(Yii::getAlias('@web/images/social/facebook.png')), $facebook, ['class' => 'facebook', 'target'=>'_blank']) : '' ?>
                            <?= !empty($twitter) ? Html::a(Html::img(Yii::getAlias('@web/images/social/twitter.png')), $twitter, ['class' => 'twitter', 'target'=>'_blank']) : '' ?>
                        </div>
                    <?php } ?>
                    <div class="credit-cards">
                        <span><?= Yii::t('app', 'We accept') ?></span>
                        <img src="<?= Yii::getAlias('@web/images/credit_cards.png') ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <?php foreach ($list as $column) { ?>
                        <div class="col-sm-4">
                            <ul class="footer-menu">
                                <?php foreach ($column as $item) { ?>
                                    <li><a href="<?= Url::to($item['url']) ?>"><?= $item['label'] ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="copyright">&copy; <?= Yii::t('app', 'Copyright {year} by {company} All rights Reserved', ['year' => date('Y'), 'company' => 'Aggua Inc.']) ?></div>
</footer>

<!-- Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="alertModalContent">Alert Message</div>
            </div>
        </div>
    </div>
</div>
