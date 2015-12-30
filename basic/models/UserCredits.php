<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_credits".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $credit
 * @property string $description
 * @property string $expires
 * @property string $used
 *
 * @property User $user
 */
class UserCredits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_credits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['credit', 'used'], 'number'],
            [['expires'], 'safe'],
            [['description'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'credit' => 'Credit',
            'description' => 'Description',
            'expires' => 'Expires',
            'used' => 'Used',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
