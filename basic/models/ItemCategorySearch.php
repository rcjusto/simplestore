<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class ItemCategorySearch extends Model
{

    public $order_by;

    public $formName = '';

    /** @var $_category Category */
    private $_category = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_by'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->scenario = '';
        $query = Product::find();

        $this->scenario = 'default';
        $this->load($params, '');

        $order_arr = !empty($this->order_by) ? explode(':', $this->order_by) : ['id', 'desc'];
        $orderField = (count($order_arr) > 0) ? $order_arr[0] : 'id';
        $orderSort = (count($order_arr) > 1 && $order_arr[1] == 'desc') ? SORT_DESC : SORT_ASC;
        $this->order_by = $orderField . ':' . ($orderSort == SORT_DESC ? 'desc' : 'asc');

        /** @var Category $category */
        if (isset($params['code']) && !empty($params['code'])) {
            $this->_category = Category::find()->where(['url_code' => $params['code']])->one();
        }
        if (is_null($this->_category) && isset($params['id']) && !empty($params['id'])) {
            $this->_category = Category::findOne($params['id']);
        }

        if (!is_null($this->_category)) {
            $arr_cats = $this->_category->fillSubCategoryArray();
            $query->andFilterWhere(['in', 'category_id', $arr_cats]);
        }

        $per_page = (isset($params['per-page'])) ? $params['per-page'] : 12;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $per_page,
            ],
            'sort' => [
                'defaultOrder' => [
                    'new' => SORT_DESC,
                ],
                'attributes' => [
                    'new' => [
                        'desc' => ['id' => SORT_DESC],
                        'asc' => false,
                        'default' => SORT_DESC,
                        'label' => Yii::t('app','Newest Arrivals'),
                    ],
                    'featured' => [
                        'asc' => false,
                        'desc' => ['featured' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('app','Featured'),
                    ],
                    'price_asc' => [
                        'desc' => false,
                        'asc' => ['price_custom' => SORT_ASC],
                        'default' => SORT_ASC,
                        'label' => Yii::t('app','Price: Low to High'),
                    ],
                    'price_desc' => [
                        'asc' => false,
                        'desc' => ['price_custom' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('app','Price: High to Low'),
                    ],
                ],
            ],
        ]);

        return $dataProvider;
    }

    public function getCategory()
    {
        return $this->_category;
    }
}
