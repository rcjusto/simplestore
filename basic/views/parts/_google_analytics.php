<?php if (!empty($gaID = app\models\Property::getPropertyValue('google_analytics_id', ''))) { ?>
<!-- Google Analytics -->
<script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', '<?=$gaID?>', 'auto');
    ga('send', 'pageview');
</script>
<script async src='//www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->
<?php } ?>