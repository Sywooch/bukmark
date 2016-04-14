<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\helpers\DateConverter;

/**
 * SummarySearch represents the model behind the search form about summarys.
 */
class SummarySearch extends EstimateEntry
{	
	/**
	 * @var integer
	 */
	public $bukmark_code;
	/**
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
            [['product_id', 'client_id'], 'integer'],
            [['bukmark_code'], 'safe'],
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
		$labels['bukmark_code'] = 'Código';
		$labels['client_id'] = 'Cliente';
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
        $query = EstimateEntry::find();
		$query->innerJoinWith(['product']);
		$query->innerJoinWith(['estimate.receipt']);

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
			'product_id' => $this->product_id,
			'estimate.client_id' => $this->client_id,
        ]);
		
		 $query->andFilterWhere(['like', 'product.bukmark_code', $this->bukmark_code]);
		
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
		
		$query->andFilterWhere(['>=', 'receipt.created_date', $this->from_date]);
		$query->andFilterWhere(['<=', 'receipt.created_date', $this->to_date]);
		
		// Only checked entries are used
		$query->andFilterWhere(['checked' => true]);

        return $dataProvider;
    }
}
