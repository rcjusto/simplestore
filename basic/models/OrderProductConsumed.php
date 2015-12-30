<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_product_consumed".
 *
 * @property integer $order_product_id
 * @property string $consumed_date
 * @property integer $quantity
 *
 * @property OrderProducts $orderProduct
 */
class OrderProductConsumed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_product_consumed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_product_id', 'consumed_date'], 'required'],
            [['order_product_id', 'quantity'], 'integer'],
            [['consumed_date'], 'safe'],
            [['order_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProducts::className(), 'targetAttribute' => ['order_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_product_id' => Yii::t('app', 'Order Product ID'),
            'consumed_date' => Yii::t('app', 'Consumed Date'),
            'quantity' => Yii::t('app', 'Quantity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProduct()
    {
        return $this->hasOne(OrderProducts::className(), ['id' => 'order_product_id']);
    }
}
