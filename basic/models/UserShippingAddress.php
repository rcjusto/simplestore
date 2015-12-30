<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_shipping_address".
 *
 * @property integer $id
 * @property integer $user_id
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
 * @property string $password
 *
 * @property User $user
 */
class UserShippingAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_shipping_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','shipping_contact','shipping_email'], 'required'],
            [['shipping_email'], 'email'],
            [['user_id'], 'integer'],
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'shipping_address1' => 'Shipping Address1',
            'shipping_address2' => 'Shipping Address2',
            'shipping_city' => 'Shipping City',
            'shipping_state' => 'Shipping State',
            'shipping_zip' => 'Shipping Zip',
            'shipping_country' => 'Shipping Country',
            'shipping_phone' => Yii::t('app','Phone Number'),
            'shipping_email' => Yii::t('app','Email Address'),
            'shipping_contact' => Yii::t('app','First and Last Name'),
            'shipping_contact2' => Yii::t('app','Extra Contact'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
