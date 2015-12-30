<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\web\View;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $created
 * @property integer $user_id
 * @property string $billing_address1
 * @property string $billing_address2
 * @property string $billing_city
 * @property string $billing_state
 * @property string $billing_country
 * @property string $billing_zip
 * @property string $billing_phone
 * @property string $billing_first_name
 * @property string $billing_last_name
 * @property string $total
 * @property string $message
 * @property integer $status
 *
 * @property User $user
 * @property OrderCosts[] $orderCosts
 * @property OrderProducts[] $orderProducts
 * @property OrderShipping $orderShipping
 */
class Order extends ActiveRecord
{

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'user_id'], 'required'],
            [['created', 'message'], 'safe'],
            [['user_id', 'status'], 'integer'],
            [['total'], 'number'],
            [['billing_address1', 'billing_address2', 'billing_city', 'billing_state', 'billing_first_name', 'billing_last_name', 'billing_country'], 'string', 'max' => 255],
            [['billing_zip', 'billing_phone'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'user_id' => 'User ID',
            'billing_address1' => 'Billing Address1',
            'billing_address2' => 'Billing Address2',
            'billing_city' => 'Billing City',
            'billing_state' => 'Billing State',
            'billing_country' => 'Billing Country',
            'billing_zip' => 'Billing Zip',
            'billing_phone' => 'Billing Phone',
            'billing_contact' => 'Billing Contact',
            'total' => 'Total',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCosts()
    {
        return $this->hasMany(OrderCosts::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderShipping()
    {
        return $this->hasOne(OrderShipping::className(), ['order_id' => 'id']);
    }

    /**
     * @return OrderPayment
     */
    public function getOrderPayment()
    {
        return OrderPayment::find()->where(['order_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
    }

    /**
     * @param $shopCart ShoppingCart
     * @param $user User
     * @param $destination UserShippingAddress
     * @return Order
     */
    public static function generateFromCart($shopCart, $user, $destination)
    {
        $model = new Order();
        $model->user_id = $user->id;
        $model->created = date('Y-m-d H:i:s');
        $model->billing_address1 = $user->billing_address1;
        $model->billing_address2 = $user->billing_address2;
        $model->billing_city = $user->billing_city;
        $model->billing_state = $user->billing_state;
        $model->billing_country = $user->billing_country;
        $model->billing_zip = $user->billing_zip;
        $model->billing_phone = $user->billing_phone;
        $model->billing_first_name = $user->billing_first_name;
        $model->billing_last_name = $user->billing_last_name;
        $model->total = $shopCart->getTotal();
        $model->status = Order::STATUS_PENDING;
        if ($model->save()) {
            // save products
            foreach ($shopCart->items as $item) {
                $itemProd = $item->getProduct();
                if (!is_null($itemProd)) {
                    $op = new OrderProducts();
                    $op->order_id = $model->id;
                    $op->code = $itemProd->code;
                    $op->cost = $itemProd->cost;
                    $op->description = $itemProd->getName(Yii::$app->language);
                    $op->price = $item->price;
                    $op->product_id = $item->product_id;
                    $op->quantity = $item->quantity;
                    $op->product_data = $item->product_data;
                    $op->save();
                }
            }

            // save destination
            if (!is_null($destination)) {
                $shipping = new OrderShipping();
                $shipping->order_id = $model->id;
                $shipping->shipping_address1 = $destination->shipping_address1;
                $shipping->shipping_address2 = $destination->shipping_address2;
                $shipping->shipping_city = $destination->shipping_city;
                $shipping->shipping_state = $destination->shipping_state;
                $shipping->shipping_zip = $destination->shipping_zip;
                $shipping->shipping_country = $destination->shipping_country;
                $shipping->shipping_phone = $destination->shipping_phone;
                $shipping->shipping_email = $destination->shipping_email;
                $shipping->shipping_contact = $destination->shipping_contact;
                $shipping->shipping_contact2 = $destination->shipping_contact2;
                $shipping->save();
            }
            return $model;
        } else {
            return null;
        }
    }

    public function beforeDelete()
    {
        OrderShipping::deleteAll(['order_id' => $this->id]);
        OrderPayment::deleteAll(['order_id' => $this->id]);
        OrderProducts::deleteAll(['order_id' => $this->id]);
        return parent::beforeDelete();
    }

    public function generateEmailToBuyer()
    {
        if (!empty($emailTo = $this->user->email)) {
            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app', 'New order at {store_name}', ['store_name' => Property::getPropertyValue('store_name', '')]);
            $mail->body = StoreUtils::renderView('//mail/_order_buyer', ['model' => $this]);
            $mail->save();
        }
    }

    public function generateEmailToAdmin()
    {
        if (!empty($emailTo = Yii::$app->params['adminEmail'])) {
            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app', 'New order at {store_name}', ['store_name' => Property::getPropertyValue('store_name', '')]);
            $mail->body = StoreUtils::renderView('//mail/_order_admin', ['model' => $this]);
            $mail->save();
        }
    }

    public function generateEmailToDestination()
    {
        if (!is_null($this->orderShipping) && !empty($emailTo = $this->orderShipping->shipping_email)) {

            $providers = [];
            foreach ($this->orderProducts as $item) {
                if (!is_null($item->product)) {
                    $provID = !empty($item->product->provider_id) ? $item->product->provider_id : 0;
                    if (!in_array($provID, $providers)) {
                        $providers[] = $provID;
                    }
                }
            }

            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app', 'New order at {store_name} from {user_name}', ['store_name' => Property::getPropertyValue('store_name', ''), 'user_name' => $this->billing_first_name . ' ' . $this->billing_last_name]);
            $mail->body = StoreUtils::renderView('//mail/_order_destination', ['model' => $this, 'providers' => $providers]);
            $mail->save();
        }
    }

    public function generateEmailToProviders()
    {
        // split order in providers
        $providers = $this->getProductsByProviders();

        foreach ($providers as $provID => $items) {

            /** @var Provider $provider */
            $provider = Provider::findOne($provID);

            $mail = new Mail();
            $mail->subject = Yii::t('app', 'New order at {store_name}', ['store_name' => Property::getPropertyValue('store_name', '')]);
            $mail->body = StoreUtils::renderView('//mail/_order_provider', ['model' => $this, 'provider' => $provider, 'items' => $items]);

            if (!is_null($provider) && !empty($provider->email)) {
                $mail->to = $provider->email;
            } else {
                $mail->to = Yii::$app->params['adminEmail'];
            }

            $mail->save();
        }

    }

    public function getProductsByProviders()
    {
        $providers = [];
        foreach ($this->orderProducts as $item) {
            if (!is_null($item->product)) {
                $provID = !empty($item->product->provider_id) ? $item->product->provider_id : 0;
                if (array_key_exists($provID, $providers)) {
                    $providers[$provID][] = $item;
                } else {
                    $providers[$provID] = [$item];
                }
            }
        }
        return $providers;
    }

    /**
     * @param $provider_id
     * @return OrderProducts[]
     */
    public function getProviderProducts($provider_id)
    {
        $result = [];
        foreach ($this->orderProducts as $item) {
            if (!is_null($item->product)) {
                if (!empty($item->product->provider_id) && $item->product->provider_id == $provider_id)
                    $result[] = $item;
            }
        }
        return $result;
    }

    public function getProviderCost($provider_id)
    {
        $result = 0;
        foreach ($this->orderProducts as $item) {
            if (!is_null($item->product)) {
                if (!empty($item->product->provider_id) && $item->product->provider_id == $provider_id)
                    $result += $item->cost * $item->quantity;
            }
        }
        return $result;
    }

    public function getProviderPending($provider_id)
    {
        $result = 0;
        foreach ($this->orderProducts as $item) {
            if (!is_null($item->product)) {
                if (!empty($item->product->provider_id) && $item->product->provider_id == $provider_id)
                    $result += $item->quantity - $item->consumed;
            }
        }
        return $result;
    }

    public function getBuyerName()
    {
        return $this->billing_first_name . ' ' . $this->billing_last_name;
    }

    public function getTotalProducts()
    {
        $result = 0;
        foreach ($this->orderProducts as $item) {
            $result += $item->quantity;
        }
        return $result;
    }

    public function getTotalConsumed()
    {
        $result = 0;
        foreach ($this->orderProducts as $item) {
            $result += $item->consumed;
        }
        return $result;
    }

    public function getTotalPending()
    {
        return $this->getTotalProducts() - $this->getTotalConsumed();
    }

    public function getStatusDesc($text = true, $icon = false)
    {
        $result = '';
        if ($icon) {
            if ($this->status == Order::STATUS_APPROVED) $result = Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok-sign', 'style' => 'color:#008800;']);
            elseif ($this->status == Order::STATUS_REJECTED) $result =  Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove-sign', 'style' => 'color:#AA0000;']);
            else $result =  Html::tag('span', '', ['class' => 'glyphicon glyphicon-question-sign', 'style' => 'color:#666666;']);
        }
        if ($text) {
            if (!empty($result)) $result .= ' ';
            $result .= self::getStatuses($this->status);
        }
        return $result;
    }

    public static function getStatuses($key = null)
    {
        $list = [
            0 => Yii::t('app', 'Pending'),
            1 => Yii::t('app', 'Approved'),
            10 => Yii::t('app', 'Rejected'),
        ];
        return !is_null($key) ? $list[$key] : $list;
    }


}
