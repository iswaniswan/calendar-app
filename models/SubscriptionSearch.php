<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form of `app\models\Subscription`.
 */
class SubscriptionSearch extends Subscription
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['end_point', 'expiration_time', 'auth', 'p256dh', 'raw'], 'safe'],
            [['f_status'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Subscription::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'f_status' => $this->f_status,
        ]);

        $query->andFilterWhere(['ilike', 'end_point', $this->end_point])
            ->andFilterWhere(['ilike', 'expiration_time', $this->expiration_time])
            ->andFilterWhere(['ilike', 'auth', $this->auth])
            ->andFilterWhere(['ilike', 'p256dh', $this->p256dh])
            ->andFilterWhere(['ilike', 'raw', $this->raw]);

        return $dataProvider;
    }
}
