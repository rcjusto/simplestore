<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CommentForm extends Model
{
    public $name;
    public $email;
    public $body;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'body'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app','Your Name'),
            'email' => Yii::t('app','Your Email Address'),
            'body' => Yii::t('app','Comment'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {

            $mail = new Mail();
            $mail->subject = Yii::t('app','Comment received on {store}', ['store'=>Property::getPropertyValue('store_name','')]);
            $mail->body = StoreUtils::renderView('//mail/_comment', ['model' => $this]);
            $mail->to = $email;
            $mail->save();

            return true;
        }
        return false;
    }
}
