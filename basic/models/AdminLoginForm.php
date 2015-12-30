<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AdminLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    public function loginProvider() {
        /** @var Provider $model */
        $model = Provider::find()->where(['email'=>$this->username])->one();
        if (!is_null($model) && ($model->password==$this->password)) {
            return $model;
        } else {
            $this->addError('password', Yii::t('app', 'Incorrect User or Password'));
            return null;
        }
    }

    public function loginAdmin() {
        /** @var Admin $model */
        $model = Admin::find()->where(['email'=>$this->username])->one();
        if (!is_null($model) && ($model->password==$this->password)) {
            return $model;
        } else {
            $this->addError('password', Yii::t('app', 'Incorrect User or Password'));
            return null;
        }
    }

    public function loginDestination() {
        /** @var DestinationAccount $model */
        $model = DestinationAccount::find()->where(['email'=>$this->username])->one();
        if (!is_null($model) && ($model->password==$this->password)) {
            return $model;
        } else {
            $this->addError('password', Yii::t('app', 'Incorrect User or Password'));
            return null;
        }
    }

}
