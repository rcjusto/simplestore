<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $url_code
 * @property integer $parent
 * @property integer $show_in_menu
 * @property string $template
 *
 * @property CategoryLang[] $categoryLangs
 * @property Product[] $products
 */
class Category extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'show_in_menu'], 'integer'],
            [['url_code'], 'string', 'max' => 1024],
            [['template'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_code' => 'Url Code',
            'parent' => 'Parent',
            'show_in_menu' => 'Show In Menu',
            'template' => 'Template',
        ];
    }

    public function beforeSave($insert)
    {
        if (empty($this->parent)) $this->parent = 0;
        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (Product::find()->where(['category_id'=>$this->id])->exists()) {
                Yii::$app->session->setFlash('categories_error', Yii::t('app','Cannot delete category <strong>{cat_name}</strong> because it has products assigned.',['cat_name'=>$this->getName()]));
                return false;
            } elseif (Category::find()->where(['parent'=>$this->id])->exists())  {
                Yii::$app->session->setFlash('categories_error', Yii::t('app','Cannot delete category <strong>{cat_name}</strong> because it has categories assigned.',['cat_name'=>$this->getName()]));
                return false;
            } else {
                CategoryLang::deleteAll(['category_id'=>$this->id]);
                Yii::$app->session->setFlash('categories_info', Yii::t('app','Category <strong>{cat_name}</strong> was successfully deleted.',['cat_name'=>$this->getName()]));
                return true;
            }
        } else {
            return false;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryLangs()
    {
        return $this->hasMany(CategoryLang::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    public function getCategoryLang($lang, $create_it = false)
    {
        $model = !empty($this->id) ? CategoryLang::findOne(['category_id'=>$this->id, 'lang'=>$lang]) : null;
        if (is_null($model) && !empty($this->id) && $create_it) {
            $model = new CategoryLang();
            $model->category_id = $this->id;
            $model->lang = $lang;
        }
        return $model;
    }

    public function getName($lang = null) {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getCategoryLang($lang);
        return !is_null($model) ? $model->name : null;
    }

    public function setName($lang, $val) {
        $model = $this->getCategoryLang($lang, true);
        $model->name = $val;
        $model->save();
    }

    public function getContent($lang = null) {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getCategoryLang($lang);
        return !is_null($model) ? $model->content : null;
    }

    public function setContent($lang, $val) {
        $model = $this->getCategoryLang($lang, true);
        $model->content = $val;
        $model->save();
    }

    public function getParentName($lang = null) {
        $parent =!empty($this->parent) ? Category::findOne($this->parent) : null;
        if (!is_null($parent)) {
            if (empty($lang)) $lang = Property::getDefaultLanguage();
            $model = $parent->getCategoryLang($lang);
            return !is_null($model) ? $model->name : null;
        }
        return null;
    }


    public function getUrl() {
        return (!empty($this->url_code)) ? Url::to(['catalog/category', 'code'=>$this->url_code]) : Url::to(['catalog/category', 'id'=>$this->id]);
    }

    public function updateURLCode($name = null) {
        if (empty($name)) {
            $name = $this->getName();
        }

        $code = StoreUtils::url_slug($name);
        $urlCode = $code;
        $exists = Category::find()->where('id<>:id and url_code=:code',[':id'=>$this->id, ':code'=>$urlCode])->exists();
        $index = 1;
        while($exists) {
            $urlCode = $code . '-' . ($index++);
            $exists = Category::find()->where('id<>:id and url_code=:code',[':id'=>$this->id, ':code'=>$urlCode])->exists();
        }
        $this->url_code = $urlCode;
        $this->save();
    }

    public function fillSubCategoryArray($array = []) {
        $array[] = $this->id;
        /** @var Category[] $list */
        $list = Category::find()->where(['parent'=>$this->id])->all();
        foreach($list as $sub) {
            $array = $sub->fillSubCategoryArray($array);
        }
        return $array;
    }

    /**
     * @param null $parentID
     * @param bool|true $for_menu
     * @return Category[]
     */
    public static function getChildren($parentID = null, $for_menu = true) {
        $params = [];
        if (!is_null($parentID)) $params['parent'] = $parentID;
        else $params['parent'] = 'IS NULL';
        if ($for_menu) $params['show_in_menu'] = 1;
        return Category::find()->where($params)->orderBy(['url_code'=>SORT_ASC])->all();
    }

    /**
     * @param array $arr
     * @return Category[]
     */
    public function getParents($arr = []) {
        if (!is_null($parentCat = Category::findOne($this->parent))) {
            array_unshift($arr, $parentCat);
            $arr = $parentCat->getParents($arr);
        }
        return $arr;
    }

}
