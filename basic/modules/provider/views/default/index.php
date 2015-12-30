<?php
/** @var $pending \app\models\OrderProducts[] */
use app\models\Product;
use yii\helpers\Url;


?>
<div class="provider-default-index">
    <h1>Dashboard</h1>

    <div class="row">
        <div class="col-sm-6">
            <a style="float: right;margin-top: 25px;" href="<?= Url::to(['/providers/order']) ?>">See all orders</a>

            <h3>Last Pending Orders</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Created</th>
                    <th>Destinatario</th>
                    <th>Product</th>
                    <th>Pending</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pending as $op) { ?>
                    <tr>
                        <td><a href="<?=Url::to(['/provider/order/view', 'id'=>$op->order_id])?>"><?= $op->order_id ?></a></td>
                        <td><?= date('Y-m-d', strtotime($op->order->created)) ?></td>
                        <td><?= $op->order->orderShipping->shipping_contact ?></td>
                        <td><?= $op->description ?></td>
                        <td><?= $op->quantity - $op->consumed ?> item(s)</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <a style="float: right;margin-top: 25px;" href="<?=Url::to(['/provider/product'])?>">See all products</a>
            <h3>Top Sellers</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th style="text-align: right">Stock</th>
                    <th style="text-align: right">Sales</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($products as $row) {
                    /** @var Product $product */
                    $product = Product::findOne($row['product_id']);
                    $sales = $row['cant'];
                    ?>
                    <tr>
                        <td><a href="<?=Url::to(['/provider/product/update','id'=>$product->id])?>"><?=$product->code?></a></td>
                        <td><?=$product->getName()?></td>
                        <td style="text-align: right"><?=$product->stock?></td>
                        <td style="text-align: right"><?=$sales?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
