<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CreditCardForm extends Model
{
    public $profile_id;
    public $payment_profile_id;
    public $credit_card;
    public $expiration_month;
    public $expiration_year;
    public $security_code;
    public $name;
    public $zip_code;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['credit_card', 'expiration_month', 'expiration_year', 'security_code'], 'required'],
            [['security_code', 'name', 'zip_code', 'profile_id', 'payment_profile_id'], 'safe'],
        ];
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'credit_card' => Yii::t('app','Credit Card'),
            'security_code' => Yii::t('app','Security Code'),
        ];
    }

    public function getExpirationDate() {
        return $this->expiration_year . '-' . $this->expiration_month;
    }

}
