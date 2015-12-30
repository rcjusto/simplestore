<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "block".
 *
 * @property string $id
 * @property string $url_code
 * @property integer $active
 *
 * @property BlockLang[] $blockLangs
 */
class Block extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['active'], 'integer'],
            [['id'], 'string', 'max' => 45],
            [['url_code'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url_code' => Yii::t('app', 'Url Code'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlockLangs()
    {
        return $this->hasMany(BlockLang::className(), ['block_id' => 'id']);
    }

    public function getBlockLang($lang, $create_it = false)
    {
        $model = !empty($this->id) ? BlockLang::findOne(['block_id' => $this->id, 'lang' => $lang]) : null;
        if (is_null($model) && !empty($this->id) && $create_it) {
            $model = new BlockLang();
            $model->block_id = $this->id;
            $model->lang = $lang;
        }
        return $model;
    }


    public function getContent($lang = null, $default = null)
    {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getBlockLang($lang);
        return !is_null($model) ? $model->content : $default;
    }

    public function setContent($lang, $val)
    {
        $model = $this->getBlockLang($lang, true);
        $model->content = $val;
        $model->save();
    }

    public function getTitle($lang = null)
    {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getBlockLang($lang);
        return !is_null($model) ? $model->title : null;
    }

    public function setTitle($lang, $val)
    {
        $model = $this->getBlockLang($lang, true);
        $model->title = $val;
        $model->save();
    }

    public function getUrl()
    {
        return (!empty($this->url_code)) ? Url::to(['site/page', 'code' => $this->url_code]) : Url::to(['site/page', 'id' => $this->id]);
    }

    public function updateURLCode($name = null)
    {
        if (empty($this->url_code)) {
            if (empty($name)) {
                $name = $this->getTitle();
            }

            $code = StoreUtils::url_slug($name);
            $urlCode = $code;
            $exists = Block::find()->where('id<>:id and url_code=:code', [':id' => $this->id, ':code' => $urlCode])->exists();
            $index = 1;
            while ($exists) {
                $urlCode = $code . '-' . ($index++);
                $exists = Block::find()->where('id<>:id and url_code=:code', [':id' => $this->id, ':code' => $urlCode])->exists();
            }
            $this->url_code = $urlCode;
            $this->save();
        }
    }

}
