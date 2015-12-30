<?php

namespace app\models;

use app\components\AuthorizeNetCIM;
use Yii;

/**
 * This is the model class for table "user_authorize_net".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $profile_id
 * @property string $payment_profile_id
 * @property string $created
 * @property string $cc_id
 *
 * @property User $user
 */
class UserAuthorizeNet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_authorize_net';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created'], 'required'],
            [['user_id'], 'integer'],
            [['created', 'cc_id'], 'safe'],
            [['profile_id', 'payment_profile_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'profile_id' => 'Profile ID',
            'payment_profile_id' => 'Payment Profile ID',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserProfileID()
    {
        /** @var UserAuthorizeNet $model */
        $model = UserAuthorizeNet::find()->where("user_id=:uid and profile_id is not null and profile_id<>''", [':uid' => $this->user_id])->one();
        return !is_null($model) ? $model->profile_id : '';
    }

    public function beforeValidate()
    {
        if (empty($this->created)) $this->created = date('Y-m-d H:i:s');
        return parent::beforeValidate();
    }


    /**
     * @param $form CreditCardForm
     * @return bool
     */
    public function sendToAuthorize($form)
    {

        $user = $this->user;

        $authorize = new AuthorizeNetCIM();

        if (!empty($this->profile_id) && !empty($this->payment_profile_id)) {

            $authorize->updatePaymentProfile(
                $this->profile_id,
                $this->payment_profile_id,
                $user->billing_first_name,
                $user->billing_last_name,
                $user->billing_phone,
                $form->credit_card,
                $form->getExpirationDate(),
                $form->security_code,
                $user->billing_address1,
                $user->billing_city,
                $user->billing_state,
                $user->billing_zip
            );

            if (isset($res['result']) && $res['result'] == AuthorizeNetCIM::RESULT_SUCCESS) {
                if (isset($res['customerPaymentProfileId']) && !empty($res['customerPaymentProfileId'])) {

                    $payment_profile_id = $res['customerPaymentProfileId'];
                    $this->payment_profile_id = $payment_profile_id;

                    return true;

                } else {
                    $form->addError('credit_card', Yii::t('app', 'Returned empty payment profile id'));
                    return false;
                }
            } else {
                $form->addError('credit_card', isset($res['message']) ? $res['message'] : Yii::t('app', 'Error creating payment profile'));
                return false;
            }

        } else {

            $profile_id = $this->getUserProfileID();

            if (empty($profile_id)) {
                $res = $authorize->createProfile($this->user_id, $user->email);
                if (isset($res['result']) && $res['result'] == AuthorizeNetCIM::RESULT_SUCCESS) {
                    if (isset($res['customerProfileId']) && !empty($res['customerProfileId'])) {

                        $profile_id = $res['customerProfileId'];

                    } else {
                        $form->addError('credit_card', Yii::t('app', 'Empty profile id'));
                        return false;
                    }
                } elseif (isset($res['code']) && $res['code'] == 'E00039' && isset($res['message'])) {

                    // email already exists, try to find the old profile_id from the message
                    $arr = explode(" ", $res['message']);
                    if (($index = array_search('ID', $arr, true)) !== false) {
                        if (isset($arr[$index + 1]) && !empty($arr[$index + 1]) && is_numeric($arr[$index + 1])) {
                            $profile_id = $arr[$index + 1];
                        } else {
                            $form->addError('credit_card', isset($res['message']) ? $res['message'] : Yii::t('app', 'Error creating user profile'));
                            return false;
                        }
                    } else {
                        $form->addError('credit_card', isset($res['message']) ? $res['message'] : Yii::t('app', 'Error creating user profile'));
                        return false;
                    }

                } else {

                    $form->addError('credit_card', isset($res['message']) ? $res['message'] : Yii::t('app', 'Error creating user profile'));
                    return false;
                }
            }

            $res = $authorize->createPaymentProfile($profile_id, '',
                $user->billing_first_name,
                $user->billing_last_name,
                $user->billing_phone,
                $form->credit_card,
                $form->getExpirationDate(),
                $form->security_code,
                $user->billing_address1,
                $user->billing_city,
                $user->billing_state,
                $user->billing_zip);

            if (isset($res['result']) && $res['result'] == AuthorizeNetCIM::RESULT_SUCCESS) {
                if (isset($res['customerPaymentProfileId']) && !empty($res['customerPaymentProfileId'])) {

                    $payment_profile_id = $res['customerPaymentProfileId'];
                    $this->profile_id = $profile_id;
                    $this->payment_profile_id = $payment_profile_id;

                    return true;

                } else {
                    $form->addError('credit_card', Yii::t('app', 'Returned empty payment profile id'));
                    return false;
                }
            } else {
                $form->addError('credit_card', isset($res['message']) ? $res['message'] : Yii::t('app', 'Error creating payment profile'));
                return false;
            }

        }

    }

    public function getCc_info()
    {
        if (empty($this->cc_id)) {
            $authorize = new AuthorizeNetCIM();
            $res = $authorize->getPaymentProfileInfo($this->profile_id, $this->payment_profile_id);
            if (isset($res['result']) && $res['result'] == AuthorizeNetCIM::RESULT_SUCCESS && isset($res['paymentProfile'])) {
                $paymentProfile = $res['paymentProfile'];;
                if (isset($paymentProfile->payment) && isset($paymentProfile->payment->creditCard) && isset($paymentProfile->payment->creditCard->cardNumber) && !empty($paymentProfile->payment->creditCard->cardNumber)) {
                    $card = substr($paymentProfile->payment->creditCard->cardNumber, 4, 4);
                    if (!empty($card)) {
                        $this->cc_id = $card;
                        $this->save();
                    }
                }
            }
        }
        return $this->cc_id;
    }

    public function deleteProfile()
    {
        $authorize = new AuthorizeNetCIM();
        return $authorize->deletePaymentProfile($this->profile_id, $this->payment_profile_id);
    }

    /**
     * @param $order Order
     * @return bool
     */
    public function postPayment($order)
    {
        $authorize = new AuthorizeNetCIM();
        $res = $authorize->authorizeAndCapture($this->profile_id, $this->payment_profile_id, $order->total, $order->id);
        if (isset($res['result'])) {
            $payment = new OrderPayment();
            $payment->order_id = $order->id;
            $payment->profile_id = $this->profile_id;
            $payment->payment_profile_id = $this->payment_profile_id;
            $payment->amount = $order->total;
            $payment->transaction_id = isset($res['transId']) ? $res['transId'] : '';
            $payment->authorization_code = isset($res['approvalCode']) ? $res['approvalCode'] : '';
            $payment->response_code = isset($res['responseCode']) ? $res['responseCode'] : '';
            $payment->message = isset($res['responseReasonText']) ? $res['responseReasonText'] : '';
            if ($res['result'] == AuthorizeNetCIM::RESULT_SUCCESS) {
                $payment->status = 1;
                $order->status = Order::STATUS_APPROVED;
            } else {
                $payment->status = 0;
                if (isset($res['responseReasonCode'])) $payment->response_code = $res['responseReasonCode'];
                $order->status = Order::STATUS_REJECTED;
            }
            $payment->save();
            $order->save();
        }
    }

}
