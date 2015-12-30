<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_payment".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $amount
 * @property string $profile_id
 * @property string $payment_profile_id
 * @property integer $status
 * @property string $transaction_id
 * @property string $authorization_code
 * @property string $response_code
 * @property string $message
 * @property string $other_data
 *
 * @property Order $order
 */
class OrderPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'amount'], 'required'],
            [['order_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['message', 'other_data'], 'string'],
            [['profile_id', 'payment_profile_id', 'transaction_id', 'authorization_code', 'response_code'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'amount' => 'Amount',
            'profile_id' => 'Profile ID',
            'payment_profile_id' => 'Payment Profile ID',
            'status' => 'Status',
            'transaction_id' => 'Transaction ID',
            'authorization_code' => 'Authorization Code',
            'response_code' => 'Response Code',
            'message' => 'Message',
            'other_data' => 'Other Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getCardInfo() {
        /** @var UserAuthorizeNet $model */
        $model = UserAuthorizeNet::find()->where(['profile_id'=>$this->profile_id, 'payment_profile_id'=>$this->payment_profile_id])->one();
        return (!is_null($model) && !empty($model->getCc_info())) ? $model->getCc_info() : '';
    }

}
