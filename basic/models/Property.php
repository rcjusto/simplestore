<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property".
 *
 * @property string $id
 * @property string $value
 */
class Property extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['value'], 'string'],
            [['id'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
        ];
    }

    public static function getPropertyValue($key, $default = null)
    {
        $model = Property::findOne($key);
        return !is_null($model) ? $model->value : $default;
    }

    public static function setPropertyValue($key, $value = null)
    {
        $model = Property::findOne($key);
        if (is_null($model)) {
            $model = new Property();
            $model->id = $key;
        }
        $model->value = $value;
        $model->save();
    }

    public static function getLanguages()
    {
        $default = ['es', 'en'];
        return $default;
    }

    public static function getDefaultLanguage()
    {
        $languages = self::getLanguages();
        $model = Property::findOne('default_language');
        return (!is_null($model) && !empty($model->value) && in_array($model->value, $languages)) ? $model->value : $languages[0];
    }

}
