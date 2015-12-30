<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;

/**
 * This is the model class for table "destination_account".
 *
 * @property string $email
 * @property string $password
 * @property integer $active
 * @property string $activation_code
 * @property string $last_login
 */
class DestinationAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'destination_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'unique'],
            [['active'], 'integer'],
            [['last_login'], 'safe'],
            [['email', 'password'], 'string', 'max' => 255],
            [['activation_code'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'active' => Yii::t('app', 'Active'),
            'activation_code' => Yii::t('app', 'Activation Code'),
            'last_login' => Yii::t('app', 'Last Login'),
        ];
    }

    public function generateEmailForgotPassword()
    {
        if (!empty($emailTo = $this->email)) {
            $mail = new Mail();
            $mail->to = $emailTo;
            $mail->subject = Yii::t('app','Information from {store_name}',['store_name'=>Property::getPropertyValue('store_name','')]);
            $mail->body = StoreUtils::renderView('//mail/_destination_forgot_password', ['model'=>$this]);
            $mail->save();
        }
    }


}
