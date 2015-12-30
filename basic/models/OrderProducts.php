<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $code
 * @property string $description
 * @property integer $quantity
 * @property integer $consumed
 * @property float $price
 * @property float $cost
 * @property string $product_data
 * @property string $consumed_date
 * @property integer $product_id
 *
 * @property Order $order
 * @property Product $product
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'quantity', 'price'], 'required'],
            [['order_id', 'quantity', 'product_id', 'consumed'], 'integer'],
            [['price', 'cost'], 'number'],
            [['code'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 512],
            [['consumed_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'code' => 'Code',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'cost' => 'Cost',
            'product_id' => 'Product ID',
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return float
     */
    public function getSubtotal() {
        return $this->quantity * $this->price;
    }

}
