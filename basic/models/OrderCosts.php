<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_costs".
 *
 * @property integer $order_id
 * @property string $code
 * @property integer $quantity
 * @property string $cost
 * @property string $price
 * @property string $description
 *
 * @property Order $order
 */
class OrderCosts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_costs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'code', 'quantity', 'price'], 'required'],
            [['order_id', 'quantity'], 'integer'],
            [['cost', 'price'], 'number'],
            [['code'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'code' => 'Code',
            'quantity' => 'Quantity',
            'cost' => 'Cost',
            'price' => 'Price',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
