<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $destination_instructions
 *
 * @property Product[] $products
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name','email','password'], 'string', 'max' => 512],
            [['destination_instructions'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'destination_instructions' => 'Instructions',
        ];
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (Product::find()->where(['provider_id'=>$this->id])->exists()) {
                Yii::$app->session->setFlash('providers_error', Yii::t('app','Cannot delete provider <strong>{prov_name}</strong> because it has products assigned.',['prov_name'=>$this->name]));
                return false;
            } else {
                CategoryLang::deleteAll(['provider_id'=>$this->id]);
                Yii::$app->session->setFlash('providers_info', Yii::t('app','Provider <strong>{prov_name}</strong> was successfully deleted.',['prov_name'=>$this->name]));
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['provider' => 'id']);
    }
}
