<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate_entry".
 *
 * @property integer $id
 * @property integer $estimate_id
 * @property integer $product_id
 * @property integer $variant_id
 * @property integer $quantity
 * @property string $utility
 * @property string $price
 * @property string $variant_price
 */
class EstimateEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estimate_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['quantity'], 'required'],
			[['quantity'], 'integer', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'estimate_id' => 'Presupuesto',
            'product_id' => 'Producto',
            'variant_id' => 'Variante',
            'quantity' => 'Cantidad',
            'utility' => 'Utilidad',
            'price' => 'Precio',
			'variant_price' => 'Precio variante',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getEstimate()
    {
        return $this->hasOne(Estimate::className(), ['id' => 'estimate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariant()
    {
        return $this->hasOne(Variant::className(), ['id' => 'variant_id']);
    }
	
	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			$this->utility = str_replace(',', '.', $this->utility);
			$this->price = str_replace(',', '.', $this->price);
			$this->variant_price = str_replace(',', '.', $this->variant_price);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind() {
		$this->utility = str_replace('.', ',', $this->utility);
		$this->price = str_replace('.', ',', $this->price);
		$this->variant_price = str_replace('.', ',', $this->variant_price);
		parent::afterFind();
	}
}
