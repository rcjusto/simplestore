<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class DestinationForm extends Model
{

    public $shipping_address1;
    public $shipping_address2;
    public $shipping_city;
    public $shipping_state;
    public $shipping_zip;
    public $shipping_country = 'NI';
    public $shipping_phone;
    public $shipping_email;
    public $shipping_contact;
    public $shipping_contact2;


    /**
     * DestinationForm constructor.
     * @param UserShippingAddress $add
     */
    public function __construct($add)
    {
        parent::__construct();
        if (!is_null($add)) {
            $this->shipping_address1 = $add->shipping_address1;
            $this->shipping_address2 = $add->shipping_address2;
            $this->shipping_city = $add->shipping_city;
            $this->shipping_state = $add->shipping_state;
            $this->shipping_zip = $add->shipping_zip;
            $this->shipping_country = $add->shipping_country;
            $this->shipping_phone = $add->shipping_phone;
            $this->shipping_email = $add->shipping_email;
            $this->shipping_contact = $add->shipping_contact;
            $this->shipping_contact2 = $add->shipping_contact2;
        }
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['shipping_contact','shipping_email'],'required'],
            [['shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_contact', 'shipping_contact2'], 'string', 'max' => 255],
            [['shipping_zip', 'shipping_country', 'shipping_phone'], 'string', 'max' => 45],
            [['shipping_email'], 'string', 'max' => 512],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'shipping_contact' => Yii::t('app','Contact name'),
            'shipping_contact2' => Yii::t('app','Additional Contact'),
            'shipping_address1' => Yii::t('app','Address'),
            'shipping_address2' => Yii::t('app','Address (line 2)'),
            'shipping_city' => Yii::t('app','City'),
            'shipping_state' => Yii::t('app','State/Province'),
            'shipping_country' => Yii::t('app','Country'),
            'shipping_zip' => Yii::t('app','Zip code'),
            'shipping_phone' => Yii::t('app','Phone number'),
            'shipping_email' => Yii::t('app','Email address'),
        ];
    }

    /**
     * register the user
     * @param $add UserShippingAddress
     * @return bool whether the model passes validation
     */
    public function update($add)
    {
        if ($this->validate()) {
            $add->shipping_address1 = $this->shipping_address1;
            $add->shipping_address2 = $this->shipping_address2;
            $add->shipping_city = $this->shipping_city;
            $add->shipping_state = $this->shipping_state;
            $add->shipping_zip = $this->shipping_zip;
            $add->shipping_country = $this->shipping_country;
            $add->shipping_phone = $this->shipping_phone;
            $add->shipping_email = $this->shipping_email;
            $add->shipping_contact = $this->shipping_contact;
            $add->shipping_contact2 = $this->shipping_contact2;
            return $add->save();
        }
        return false;
    }
}
