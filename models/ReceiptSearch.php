<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Receipt;
use app\helpers\DateConverter;

/**
 * ReceiptSearch represents the model behind the search form about `app\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{	
	/**
	 *
	 * @var integer
	 */
	public $client_id;
	/**
	 * @var string from date filter
	 */
	public $from_date;
	/**
	 * @var string to date filter
	 */
	public $to_date;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'estimate_id', 'status', 'type', 'client_id'], 'integer'],
            [['created_date'], 'safe'],
            [['iva'], 'number'],
			[['from_date', 'to_date'], 'date', 'format' => 'dd/MM/yyyy'],
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
	 * @inheritdoc
	 */
	public function attributeLabels() {
		$labels = parent::attributeLabels();
		$labels['from_date'] = 'Desde';
		$labels['to_date'] = 'Hasta';
		return $labels;
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
		$query->joinWith(['estimate']);

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
            'receipt.id' => $this->id,
            'receipt.estimate_id' => $this->estimate_id,
            'receipt.status' => $this->status,
            'receipt.created_date' => $this->created_date,
            'receipt.type' => $this->type,
            'receipt.iva' => $this->iva,
			'estimate.client_id' => $this->client_id,
        ]);
		
		
		/**
		 * Convert the to dates to mysql format.
		 * Leave them as null so they are not used
		 * by ActiveQuery::andFilterWhere().
		 */
		$fromDate = null;
		$toDate = null;
		if ($this->from_date) {
			$this->from_date = DateConverter::convert($this->from_date);
		}
		if ($this->to_date) {
			$this->to_date = DateConverter::convert($this->to_date);
		}
		
		$query->andFilterWhere(['>=', 'created_date', $this->from_date]);
		$query->andFilterWhere(['<=', 'created_date', $this->to_date]);

        return $dataProvider;
    }
}
