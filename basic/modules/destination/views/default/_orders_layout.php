{summary}
<div class='item-container'>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th><?=Yii::t('app','Order #')?></th>
            <th><?=Yii::t('app','Created')?></th>
            <th><?=Yii::t('app','Buyer')?></th>
            <th style="text-align: center"><?=Yii::t('app','Items in Order')?></th>
            <th style="text-align: center"><?=Yii::t('app','Consumed Items')?></th>
            <th style="text-align: center"><?=Yii::t('app','Pending Items')?></th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        {items}
        </tbody>
    </table>
</div>
{pager}