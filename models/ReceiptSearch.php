<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Receipt;

/**
 * ReceiptSearch represents the model behind the search form about `app\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'estimate_id', 'status', 'type'], 'integer'],
            [['created_date'], 'safe'],
            [['iva'], 'number'],
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
        $query = Receipt::find();

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
            'estimate_id' => $this->estimate_id,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'type' => $this->type,
            'iva' => $this->iva,
        ]);

        return $dataProvider;
    }
}
