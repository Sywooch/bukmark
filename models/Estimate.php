<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate".
 *
 * @property integer $id
 * @property string $total
 * @property string $cost
 * @property string $us
 */
class Estimate extends \yii\db\ActiveRecord
{
	const US_TO_ARS_MARGIN = 0.04;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estimate';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Total',
            'cost' => 'Costo',
            'us' => 'Dólar',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(EstimateEntry::className(), ['estimate_id' => 'id']);
    }
	
	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			$this->total = str_replace(',', '.', $this->total);
			$this->cost = str_replace(',', '.', $this->cost);
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
		foreach($entries as $entry) {
			$subcost = $entry->price * $entry->quantity;
			$subtotal = $subcost * (1 + $entry->utility / 100);
			$cost += $subcost;
			$total += $subtotal;
		}
		$this->cost = $cost;
		$this->total = $total;
		$this->save();
	}
}
