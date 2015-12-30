<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */

class ProductSearch extends Product
{

    public $name = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'category_id', 'provider_id', 'type', 'shipping'], 'integer'],
            [['code', 'notify','name'], 'safe'],
            [['cost', 'price_custom', 'price_percent'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'stock' => $this->stock,
            'cost' => $this->cost,
            'price_custom' => $this->price_custom,
            'price_percent' => $this->price_percent,
            'category_id' => $this->category_id,
            'provider_id' => $this->provider_id,
            'type' => $this->type,
            'shipping' => $this->shipping,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'notify', $this->notify]);

        $query->joinWith('productLangs');
        $query->andFilterWhere(['like', 'product_lang.name', $this->name]);

        return $dataProvider;
    }
}
