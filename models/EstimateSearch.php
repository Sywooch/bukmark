<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estimate;

/**
 * EstimateSearch represents the model behind the search form about `app\models\Estimate`.
 */
class EstimateSearch extends Estimate
{
	
	public $clientName;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'user_id', 'status', 'deleted'], 'integer'],
            [['title', 'request_date', 'sent_date', 'clientName'], 'safe'],
            [['total', 'cost', 'total_checked', 'cost_checked', 'us'], 'number'],
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
        $query = Estimate::find();

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
            'estimate.id' => $this->id,
            'estimate.client_id' => $this->client_id,
            'estimate.user_id' => $this->user_id,
            'estimate.status' => $this->status,
            'estimate.request_date' => $this->request_date,
            'estimate.sent_date' => $this->sent_date,
            'estimate.total' => $this->total,
            'estimate.cost' => $this->cost,
            'estimate.total_checked' => $this->total_checked,
            'estimate.cost_checked' => $this->cost_checked,
            'estimate.us' => $this->us,
            'estimate.deleted' => $this->deleted,
        ]);
		
        $query->andFilterWhere(['like', 'title', $this->title]);
		
		$query->joinWith(['client' => function($query) {
			if ($this->clientName) {
				$query->where(['like', 'name', $this->clientName]);
			}
		}])->active();

        return $dataProvider;
    }
}
