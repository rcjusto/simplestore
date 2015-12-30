<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_name".
 *
 * @property integer $product_id
 * @property string $lang
 * @property string $name
 * @property string $description
 * @property string $information
 *
 * @property Product $product
 */
class ProductLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'lang'], 'required'],
            [['product_id'], 'integer'],
            [['description', 'information'], 'string'],
            [['lang'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'lang' => 'Lang',
            'name' => 'Name',
            'description' => 'Description',
            'information' => 'Information',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
