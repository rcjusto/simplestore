<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'password', 'email', 'name', 'registered', 'billing_first_name', 'billing_last_name', 'billing_address1', 'billing_address2', 'billing_city', 'billing_state', 'billing_country', 'billing_zip', 'billing_phone'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'registered' => $this->registered,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'billing_first_name', $this->billing_first_name])
            ->andFilterWhere(['like', 'billing_address1', $this->billing_address1])
            ->andFilterWhere(['like', 'billing_address2', $this->billing_address2])
            ->andFilterWhere(['like', 'billing_city', $this->billing_city])
            ->andFilterWhere(['like', 'billing_state', $this->billing_state])
            ->andFilterWhere(['like', 'billing_country', $this->billing_country])
            ->andFilterWhere(['like', 'billing_zip', $this->billing_zip])
            ->andFilterWhere(['like', 'billing_phone', $this->billing_phone]);

        return $dataProvider;
    }
}
