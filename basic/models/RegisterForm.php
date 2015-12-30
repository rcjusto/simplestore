<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{

    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $registered;
    public $billing_address1;
    public $billing_address2;
    public $billing_city;
    public $billing_state;
    public $billing_country = 'US';
    public $billing_zip;
    public $billing_phone;

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
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['shipping_contact','shipping_email'],'required'],
            [['shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_contact', 'shipping_contact2'], 'string', 'max' => 255],
            [['shipping_zip', 'shipping_country', 'shipping_phone'], 'string', 'max' => 45],
            [['shipping_email'], 'string', 'max' => 512],
            [['email'],'uniqueUserId'],
            [['email','password','first_name','last_name'],'required'],
            [['shipping_email','email'], 'email'],
            [['email', 'billing_country', 'billing_zip', 'billing_phone'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 512],
            [['email', 'billing_address1', 'billing_address2', 'billing_city', 'billing_state'], 'string', 'max' => 255],
        ];
    }

    public function uniqueUserId($attribute, $params)
    {
        if (User::find()->where(['username'=>$this->email])->exists())
            $this->addError($attribute, Yii::t('app', 'Email address already registered'));
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app','Email address'),
            'password' => Yii::t('app','Password'),
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

            'first_name' => Yii::t('app','First Name'),
            'last_name' => Yii::t('app','Last Name'),
            'billing_address1' => Yii::t('app','Address'),
            'billing_address2' => Yii::t('app','Address (line 2)'),
            'billing_city' => Yii::t('app','City'),
            'billing_state' => Yii::t('app','State/Province'),
            'billing_country' => Yii::t('app','Country'),
            'billing_zip' => Yii::t('app','Zip code'),
            'billing_phone' => Yii::t('app','Phone number'),
            'agree_terms' => '',
        ];
    }

    /**
     * register the user
     * @return boolean whether the model passes validation
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->password = $this->password;
            $user->username = $this->email;
            $user->email = $this->email;
            $user->name = $this->first_name . ' ' . $this->last_name ;
            $user->registered = date('Y-m-d H:i:s');
            $user->billing_first_name = $this->first_name;
            $user->billing_last_name = $this->last_name;
            $user->billing_address1 = $this->billing_address1;
            $user->billing_address2 = $this->billing_address2;
            $user->billing_city = $this->billing_city;
            $user->billing_state = $this->billing_state;
            $user->billing_country = $this->billing_country;
            $user->billing_zip = $this->billing_zip;
            $user->billing_phone = $this->billing_phone;
            if ($user->save()) {

                $sa = new UserShippingAddress();
                $sa->user_id = $user->id;
                $sa->shipping_address1 = $this->shipping_address1;
                $sa->shipping_address2 = $this->shipping_address2;
                $sa->shipping_city = $this->shipping_city;
                $sa->shipping_state = $this->shipping_state;
                $sa->shipping_zip = $this->shipping_zip;
                $sa->shipping_country = $this->shipping_country;
                $sa->shipping_phone = $this->shipping_phone;
                $sa->shipping_email = $this->shipping_email;
                $sa->shipping_contact = $this->shipping_contact;
                $sa->shipping_contact2 = $this->shipping_contact2;
                $sa->save();

                $user->generateEmailWelcome();

                return Yii::$app->user->login($user, 0);
            }
        }
        return false;
    }
}
