<?php

namespace app\models;

use app\components\StoreUtils;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $code
 * @property integer $active
 * @property integer $stock
 * @property string $cost
 * @property string $price_custom
 * @property string $price_percent
 * @property integer $category_id
 * @property integer $provider_id
 * @property string $notify
 * @property integer $type
 * @property integer $shipping
 * @property integer $featured
 * @property string $url_code
 *
 * @property OrderProducts[] $orderProducts
 * @property Category $category
 * @property Provider $provider
 * @property ProductLang[] $productLangs
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'category_id'], 'required'],
            [['active', 'stock', 'category_id', 'provider_id', 'type', 'shipping', 'featured'], 'integer'],
            [['cost', 'price_custom', 'price_percent'], 'number'],
            [['code'], 'string', 'max' => 45],
            [['notify','url_code'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'active' => Yii::t('app', 'Active'),
            'stock' => Yii::t('app', 'Stock'),
            'cost' => Yii::t('app', 'Cost'),
            'price_custom' => Yii::t('app', 'Price'),
            'price_percent' => Yii::t('app', 'Percent over cost'),
            'category_id' => Yii::t('app', 'Category'),
            'provider_id' => Yii::t('app', 'Provider'),
            'notify' => Yii::t('app', 'Notify when sale'),
            'type' => Yii::t('app', 'Product Type'),
            'shipping' => Yii::t('app', 'Need Shipping'),
            'featured' => Yii::t('app', 'Featured'),
        ];
    }

    public function beforeValidate()
    {
        if (empty($this->stock)) $this->stock = 0;
        if (empty($this->featured)) $this->featured = 0;
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }


    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (OrderProducts::find()->where(['product_id'=>$this->id])->exists()) {
                Yii::$app->session->setFlash('products_error', Yii::t('app','Cannot delete product <strong>{prod_name}</strong> because it was previosly ordered.',['prod_name'=>$this->getName()]));
                return false;
            } else {
                ProductLang::deleteAll(['product_id'=>$this->id]);
                Yii::$app->session->setFlash('products_info', Yii::t('app','Product <strong>{prod_name}</strong> successfully deleted.',['prod_name'=>$this->getName()]));
                return true;
            }
        } else {
            return false;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Provider::className(), ['id' => 'provider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLangs()
    {
        return $this->hasMany(ProductLang::className(), ['product_id' => 'id']);
    }

    public function getProductLang($lang, $create_it = false)
    {
        $model = !empty($this->id) ? ProductLang::findOne(['product_id'=>$this->id, 'lang'=>$lang]) : null;
        if (is_null($model) && !empty($this->id) && $create_it) {
            $model = new ProductLang();
            $model->product_id = $this->id;
            $model->lang = $lang;
        }
        return $model;
    }

    public function getListImage() {
        $list = $this->getListImages();
        return count($list)>0 ? $list[0] : '';
    }
    public function getListImages() {
        return ImageManager::removeFolder(ImageManager::getProductImages($this->id, ImageManager::PRODUCT_LIST));
    }

    public function getDetailImages() {
        return ImageManager::removeFolder(ImageManager::getProductImages($this->id, ImageManager::PRODUCT_DETAIL));
    }

    public function getName($lang = null) {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getProductLang($lang);
        return !is_null($model) ? $model->name : null;
    }

    public function setName($lang, $val) {
        $model = $this->getProductLang($lang, true);
        $model->name = $val;
        $model->save();
    }

    public function getDescription($lang = null) {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getProductLang($lang);
        return !is_null($model) ? $model->description : null;
    }

    public function setDescription($lang, $val) {
        $model = $this->getProductLang($lang, true);
        $model->description = $val;
        $model->save();
    }

    public function getInformation($lang = null) {
        if (empty($lang)) $lang = Property::getDefaultLanguage();
        $model = $this->getProductLang($lang);
        return !is_null($model) ? $model->information : null;
    }

    public function setInformation($lang, $val) {
        $model = $this->getProductLang($lang, true);
        $model->information = $val;
        $model->save();
    }

    public function getUrl() {
        return (!empty($this->url_code)) ? Url::to(['catalog/product', 'code'=>$this->url_code]) : Url::to(['catalog/product', 'id'=>$this->id]);
    }

    public function updateURLCode($name = null) {
        if (empty($name)) {
            $name = $this->getName();
        }

        $code = StoreUtils::url_slug($name);
        $urlCode = $code;
        $exists = Product::find()->where('id<>:id and url_code=:code',[':id'=>$this->id, ':code'=>$urlCode])->exists();
        $index = 1;
        while($exists) {
            $urlCode = $code . '-' . ($index++);
            $exists = Product::find()->where('id<>:id and url_code=:code',[':id'=>$this->id, ':code'=>$urlCode])->exists();
        }
        $this->url_code = $urlCode;
        $this->save();
    }

    public static function getProductTypes() {
        return [
            0 => 'Producto Fisico',
            1 => 'Servicio',
        ];
    }

}
