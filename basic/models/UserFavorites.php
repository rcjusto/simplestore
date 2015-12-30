<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_favorites".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property string $created
 */
class UserFavorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_favorites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
            [['created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'created' => 'Created',
        ];
    }
}
