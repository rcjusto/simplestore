<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $registered
 * @property string $billing_first_name
 * @property string $billing_last_name
 * @property string $billing_address1
 * @property string $billing_address2
 * @property string $billing_city
 * @property string $billing_state
 * @property string $billing_country
 * @property string $billing_zip
 * @property string $billing_phone
 *
 * @property Order[] $orders
 * @property UserCredits[] $userCredits
 * @property UserShippingAddress[] $userShippingAddresses
 * @property UserAuthorizeNet[] $userAuthorizeNet
 */
class User extends ActiveRecord implements IdentityInterface
{

    const USER_SHOPCART = "shopcart";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registered'], 'safe'],
            [['username'], 'unique'],
            [['username', 'billing_country', 'billing_zip', 'billing_phone'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 512],
            [['email', 'name', 'billing_first_name', 'billing_last_name', 'billing_address1', 'billing_address2', 'billing_city', 'billing_state'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'name' => 'Name',
            'registered' => 'Registered',
            'billing_first_name' => 'First Name',
            'billing_last_name' => 'Last Name',
            'billing_address1' => 'Billing Address1',
            'billing_address2' => 'Billing Address2',
            'billing_city' => 'Billing City',
            'billing_state' => 'Billing State',
            'billing_country' => 'Billing Country',
            'billing_zip' => 'Billing Zip',
            'billing_phone' => 'Billing Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCredits()
    {
        return $this->hasMany(UserCredits::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserShippingAddresses()
    {
        return $this->hasMany(UserShippingAddress::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuthorizeNet()
    {
        return $this->hasMany(UserAuthorizeNet::className(), ['user_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where(['username' => $token])->one();
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->id === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function getFullname() {
        return $this->billing_first_name . ' ' . $this->billing_last_name;
    }

    /**
     * @return UserShippingAddress|null
     */
    public function getDefaultDestination()
    {
        // search last order
        if (($order = $this->getLastOrder()) != null) {
            if (!is_null($order->orderShipping)) {
                $ship = $order->orderShipping;
                foreach ($this->userShippingAddresses as $add) {
                    if ($add->shipping_contact == $ship->shipping_contact) {
                        return $add;
                    }
                }
            }

        }
        return count($this->userShippingAddresses) > 0 ? $this->userShippingAddresses[0] : null;
    }

    public function generateEmailWelcome()
    {
        if (!empty($emailTo = $this->email)) {
            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app','Welcome to {store_name}',['store_name'=>Property::getPropertyValue('store_name','')]);
            $mail->body = StoreUtils::renderView('//mail/_welcome', ['user'=>$this]);
            $mail->save();
        }
    }

    public function generateEmailForgotPassword()
    {
        if (!empty($emailTo = $this->email)) {
            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app','Information from {store_name}',['store_name'=>Property::getPropertyValue('store_name','')]);
            $mail->body = StoreUtils::renderView('//mail/_forgot_password', ['user'=>$this]);
            $mail->save();
        }
    }

    /**
     * @return Order
     */
    public function getLastOrder()
    {
        return Order::find()->where(['user_id' => $this->id])->orderBy(['created' => SORT_DESC])->one();
    }

    public function getNumOrders() {
        return Order::find()->where(['user_id' => $this->id])->count();
    }

    public function getProperty($property, $defaultValue) {
        /** @var UserProperty $model */
        $model = UserProperty::findOne(['user_id'=>$this->id, 'property'=>$property]);
        return !is_null($model) ? $model->value : $defaultValue;
    }

    public function delProperty($property) {
        /** @var UserProperty $model */
        $model = UserProperty::findOne(['user_id'=>$this->id, 'property'=>$property]);
        if (!is_null($model)) $model->delete();
    }

    public function setProperty($property, $value) {
        /** @var UserProperty $model */
        $model = UserProperty::findOne(['user_id'=>$this->id, 'property'=>$property]);
        if (is_null($model)) {
            $model = new UserProperty();
            $model->user_id = $this->id;
            $model->property = $property;
        }
        $model->value = $value;
        $model->save();
    }

    /**
     * @param $shopCart ShoppingCart
     */
    public function updateUserShopCart($shopCart) {
        $data = json_encode($shopCart->items);
        $this->setProperty(self::USER_SHOPCART, $data);
    }

    public function resetUserShopCart() {
        $this->delProperty(self::USER_SHOPCART);
    }

    /**
     * @param $shopCart ShoppingCart
     */
    public function restoreUserShopCart($shopCart) {
        $jsonData = $this->getProperty(self::USER_SHOPCART, null);
        if (!empty($jsonData) && !empty($data = json_decode($jsonData, true))) {
            foreach($data as $item) {
                /** @var Product $product */
                $product = Product::findOne($item['product_id']);
                if (!is_null($product)) {
                    $shopCart->addProduct($product, $item['quantity'], $item['product_data']);
                }
            }
        }
    }

}
