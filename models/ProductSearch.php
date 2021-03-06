<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['id', 'category_id', 'supplier_id'], 'integer'],
			[['title', 'supplier_code', 'bukmark_code', 'description'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data supplier instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = Product::find()->active();

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
			'category_id' => $this->category_id,
			'supplier_id' => $this->supplier_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
				->andFilterWhere(['like', 'supplier_code', $this->supplier_code])
				->andFilterWhere(['like', 'bukmark_code', $this->bukmark_code])
				->andFilterWhere(['like', 'description', $this->description]);

		return $dataProvider;
	}

}
