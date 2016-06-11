<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property integer $id
 * @property string $zone
 * @property integer $active
 * @property string $link
 * @property string $target
 * @property integer $position
 */
class Banner extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zone'], 'required'],
            [['active','position'], 'integer'],
            [['zone','target'], 'string', 'max' => 45],
            [['link'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'zone' => Yii::t('app', 'Zone'),
            'active' => Yii::t('app', 'Active'),
            'link' => Yii::t('app', 'Link'),
            'target' => Yii::t('app', 'Target'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    public function getImage($lang = null) {
        if (empty($lang)) {
            $lang = Yii::$app->language;
        }
        return ImageManager::removeFolderStr(ImageManager::getBannerImage($this->id, $lang));
    }

    public function getTarget() {
        return array_key_exists($this->target, self::$targets) ? $this->target : '_blank';
    }

    public static $targets = [
        '_blank' => 'Ventana Nueva',
        '_self' => 'Misma Ventana',
    ];

}
