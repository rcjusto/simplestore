<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ProfileForm extends Model
{

    public $password;
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

    /**
     * ProfileForm constructor.
     * @param User $user
     */
    public function __construct($user)
    {
        parent::__construct();
        if (!is_null($user)) {
            $this->first_name = $user->billing_first_name;
            $this->last_name = $user->billing_last_name;
            $this->billing_address1 = $user->billing_address1;
            $this->billing_address2 = $user->billing_address2;
            $this->billing_city = $user->billing_city;
            $this->billing_state = $user->billing_state;
            $this->billing_country = $user->billing_country;
            $this->billing_zip = $user->billing_zip;
            $this->billing_phone = $user->billing_phone;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['first_name','last_name'],'required'],
            [['billing_country', 'billing_zip', 'billing_phone'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 512],
            [[ 'billing_address1', 'billing_address2', 'billing_city', 'billing_state'], 'string', 'max' => 255],

        ];
    }



    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app','Email address'),
            'password' => Yii::t('app','Password'),
            'first_name' => Yii::t('app','First Name'),
            'last_name' => Yii::t('app','Last Name'),
            'billing_address1' => Yii::t('app','Address'),
            'billing_address2' => Yii::t('app','Address (line 2)'),
            'billing_city' => Yii::t('app','City'),
            'billing_state' => Yii::t('app','State/Province'),
            'billing_country' => Yii::t('app','Country'),
            'billing_zip' => Yii::t('app','Zip code'),
            'billing_phone' => Yii::t('app','Phone number'),
        ];
    }

    /**
     * register the user
     * @param $user User
     * @return bool whether the model passes validation
     */
    public function update($user)
    {
        if ($this->validate()) {
            if (!empty($this->password)) $user->password = $this->password;
            $user->name = $this->first_name . ' ' . $this->last_name ;
            $user->registered = $this->registered;
            $user->billing_first_name = $this->first_name;
            $user->billing_last_name = $this->last_name;
            $user->billing_address1 = $this->billing_address1;
            $user->billing_address2 = $this->billing_address2;
            $user->billing_city = $this->billing_city;
            $user->billing_state = $this->billing_state;
            $user->billing_country = $this->billing_country;
            $user->billing_zip = $this->billing_zip;
            $user->billing_phone = $this->billing_phone;
            return $user->save();
        }
        return false;
    }
}
