<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Model
{

    public $provider_id = null;
    public $id;
    public $buyer;
    public $destination;
    public $status;
    public $created_ini;
    public $created_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider_id', 'id', 'buyer', 'destination', 'status', 'created_ini', 'created_end'], 'safe'],
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
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!empty($this->provider_id)) {
            $query->leftJoin('order_products', "order.id = order_products.order_id");
            $query->leftJoin('product', "product.id = order_products.product_id");
            $query->andFilterWhere(['=', 'product.provider_id', $this->provider_id]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        if (!empty($this->buyer)) {
            $query->andFilterWhere([
                'or',
                ['like', 'billing_first_name', $this->buyer],
                ['like', 'billing_last_name', $this->buyer]
            ]);
        }

        if (!empty($this->destination)) {
            $query->leftJoin('order_shipping', "order.id = order_shipping.order_id");
            $query->andFilterWhere([
                'or',
                ['like', 'order_shipping.shipping_contact', $this->destination],
                ['like', 'order_shipping.shipping_email', $this->destination]
            ]);
        }

        $query->andFilterWhere(['>', 'created', $this->created_ini]);
        $query->andFilterWhere(['<', 'created', $this->created_end]);

        $query->distinct();

        return $dataProvider;
    }
}
