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

	/**
	 * @inheritdoc
	 */
	public function afterFind() {
		$this->total = str_replace('.', ',', $this->total);
		$this->cost = str_replace('.', ',', $this->cost);
		$this->us = str_replace('.', ',', $this->us);
		parent::afterFind();
	}
}
