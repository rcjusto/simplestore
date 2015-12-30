<?php
/** @var $orders Order[] */
/** @var $users User[] */

use app\models\Order;
use app\models\Product;
use app\models\User;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<div class="admin-default-index">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-sm-6">
            <a style="float: right;margin-top: 25px;" href="<?=Url::to(['/admin/order'])?>">See all orders</a>
            <h3>Latest Orders</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Order #</th>
                    <th>Created</th>
                    <th>Buyer</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: right">Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($orders as $order) { ?>
                    <tr>
                        <td><?=$order->id?></td>
                        <td><?=date('Y-m-d', strtotime($order->created))?></td>
                        <td><?=$order->getBuyerName()?></td>
                        <td style="text-align: center"><?= $order->getStatusDesc(false, true)?></td>
                        <td style="text-align: right"><?=$order->total?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <a style="float: right;margin-top: 25px;" href="<?=Url::to(['/admin/product'])?>">See all products</a>
            <h3>Top Sellers</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Provider</th>
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
                        <td><?=$product->getName()?></td>
                        <td><?=!is_null($product->provider) ? $product->provider->name : ''?></td>
                        <td style="text-align: right"><?=$sales?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <a style="float: right;margin-top: 25px;" href="<?=Url::to(['/admin/user'])?>">See all users</a>
            <h3>Registered Users</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Created</th>
                    <th style="text-align: right">Orders</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user) { ?>
                    <tr>
                        <td><?=$user->getFullname()?></td>
                        <td><?=date('Y-m-d', strtotime($user->registered))?></td>
                        <td style="text-align: right"><?=$user->getNumOrders()?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</div>
