<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate".
 *
 * @property integer $id
 * @property string $total
 * @property string $cost
 * @property string $total_checked
 * @property string $cost_checked
 * @property string $us
 */
class Estimate extends \yii\db\ActiveRecord {

	const US_TO_ARS_MARGIN = 0.04;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'estimate';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'N° de presupuesto',
			'total' => 'Total',
			'cost' => 'Costo',
			'total_checked' => 'Total(confirmado)',
			'cost_checked' => 'Costo(confirmado)',
			'us' => 'Dólar',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEntries() {
		return $this->hasMany(EstimateEntry::className(), ['estimate_id' => 'id']);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			$this->total = str_replace(',', '.', $this->total);
			$this->cost = str_replace(',', '.', $this->cost);
			$this->total_checked = str_replace(',', '.', $this->total_checked);
			$this->cost_checked = str_replace(',', '.', $this->cost_checked);
			$this->us = str_replace(',', '.', $this->us);
			return true;
		} else {
			return false;
		}
	}

	public function doEstimate() {
		$entries = $this->entries;
		$cost = 0;
		$total = 0;
		$cost_checked = 0;
		$total_checked = 0;
		foreach ($entries as $entry) {
			$subcost = ($entry->price + $entry->variant_price) * $entry->quantity;
			$subtotal = $subcost * (1 + $entry->utility / 100);
			$cost += $subcost;
			$total += $subtotal;
			if ($entry->checked) {
				$cost_checked += $subcost;
				$total_checked += $subtotal;
			}
		}
		$this->cost = $cost;
		$this->total = $total;
		$this->cost_checked = $cost_checked;
		$this->total_checked = $total_checked;
		$this->save();
	}

}
