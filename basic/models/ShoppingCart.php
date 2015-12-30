<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/29/2015
 * Time: 8:01 PM
 */

namespace app\models;

use app\controllers\BaseSiteController;
use Yii;

class ShoppingCartItem {

    private $_product;

    public $product_id;
    public $product_data;
    public $quantity;
    public $price;

    /**
     * @return float
     */
    public function getSubtotal() {
        return $this->quantity * $this->price;
    }

    /**
     * @return Product
     */
    public function getProduct() {
        if (is_null($this->_product)) {
            $this->_product = Product::findOne($this->product_id);
        }
        return $this->_product;
    }

}


class ShoppingCart
{

    /** @var ShoppingCartItem[]  */
    public $items = [];
    public $destination;
    public $payment_method;

    /**
     * @param $product Product
     * @param $quantity integer
     * @param $data string
     */
    public function addProduct($product, $quantity, $data) {

        $it = new ShoppingCartItem();
        $it->product_id = $product->id;
        $it->product_data = $data;
        $it->quantity = $quantity;
        $it->price = $product->price_custom;
        $this->items[] = $it;
        $this->updateUserCart();
    }

    public function updateQuantity($index, $quantity) {
        if (count($this->items)>$index) {
            $this->items[$index]->quantity = $quantity;
            $this->updateUserCart();
        }
    }

    public function delItem($index) {
        if (count($this->items)>$index) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->updateUserCart();
        }
    }

    /**
     * @return int
     */
    public function getNumProducts() {
        $result = 0;
        foreach($this->items as $it)
            $result += $it->quantity;
        return $result;
    }

    /**
     * @return float
     */
    public function getTotal() {
        $result = 0.0;
        foreach($this->items as $it)
            $result += $it->getSubtotal();
        return $result;
    }

    public function hasItems() {
        return count($this->items) > 0;
    }

    public function updateUserCart() {
        $controller = Yii::$app->controller;
        if ($controller instanceof BaseSiteController) {
            if (!is_null($controller->getLoggedUser())) {
                $controller->getLoggedUser()->updateUserShopCart($this);
            }
        }
    }

}