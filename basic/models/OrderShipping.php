<?php

namespace app\models;

use Yii;
use yii\base\Security;

/**
 * This is the model class for table "order_shipping".
 *
 * @property integer $order_id
 * @property string $shipping_address1
 * @property string $shipping_address2
 * @property string $shipping_city
 * @property string $shipping_state
 * @property string $shipping_zip
 * @property string $shipping_country
 * @property string $shipping_phone
 * @property string $shipping_email
 * @property string $shipping_contact
 * @property string $shipping_contact2
 *
 * @property Order $order
 */
class OrderShipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_shipping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_contact', 'shipping_contact2'], 'string', 'max' => 255],
            [['shipping_zip', 'shipping_country', 'shipping_phone'], 'string', 'max' => 45],
            [['shipping_email'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'shipping_address1' => 'Shipping Address1',
            'shipping_address2' => 'Shipping Address2',
            'shipping_city' => 'Shipping City',
            'shipping_state' => 'Shipping State',
            'shipping_zip' => 'Shipping Zip',
            'shipping_country' => 'Shipping Country',
            'shipping_phone' => 'Shipping Phone',
            'shipping_email' => 'Shipping Email',
            'shipping_contact' => 'Shipping Contact',
            'shipping_contact2' => 'Shipping Contact2',
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
     * @return null|DestinationAccount
     */
    public function getDestinationAccount()
    {
        if (!empty($this->shipping_email)) {
            $model = DestinationAccount::findOne($this->shipping_email);
            if (is_null($model)) {
                $model = new DestinationAccount();
                $model->email = $this->shipping_email;
            }
            if (empty($model->password)) {
                $model->password = Yii::$app->getSecurity()->generateRandomString(8);
            }
            $model->active = 1;
            $model->save();
            return $model;
        } else {
            return null;
        }
    }
}
