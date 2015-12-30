<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "block_lang".
 *
 * @property string $block_id
 * @property string $lang
 * @property string $content
 * @property string $title
 *
 * @property Block $block
 */
class BlockLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'lang'], 'required'],
            [['content', 'title'], 'string'],
            [['block_id', 'lang'], 'string', 'max' => 45],
            [['block_id'], 'exist', 'skipOnError' => true, 'targetClass' => Block::className(), 'targetAttribute' => ['block_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'block_id' => 'Block ID',
            'lang' => 'Lang',
            'content' => 'Content',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlock()
    {
        return $this->hasOne(Block::className(), ['id' => 'block_id']);
    }
}
