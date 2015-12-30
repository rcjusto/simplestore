<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category_lang".
 *
 * @property integer $category_id
 * @property string $lang
 * @property string $name
 * @property string $content
 *
 * @property Category $category
 */
class CategoryLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'lang'], 'required'],
            [['category_id'], 'integer'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 512],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'lang' => 'Lang',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
