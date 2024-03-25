<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form of `app\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{

    public $date_start, $date_end;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_team'], 'integer'],
            [['work_date', 'date_created', 'date_updated', 'date_deleted'], 'safe'],
            [['date_start', 'date_end'], 'safe']
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
        $query = Schedule::find();

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
            'work_date' => $this->work_date,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'date_deleted' => $this->date_deleted,
            'id_team' => $this->id_team,
        ]);

        if (@$params['date_start'] != null and @$params['date_end'] != null) {
            $query->andWhere(['between', 'work_date', $params['date_start'], $params['date_end']]);
        }

        return $dataProvider;
    }
}
