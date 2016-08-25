<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate_entry".
 *
 * @property integer $id
 * @property integer $estimate_id
 * @property integer $product_id
 * @property integer $product_image_id
 * @property integer $rank a higher rank gets displayed first
 * @property integer $quantity
 * @property string $utility
 * @property string $price
 * @property integer $currency
 * @property string $variant_price
 * @property integer $variant_currency
 * @property string $description
 * @property boolean $checked
 * @property boolean $sample_delivered
 */
class EstimateEntry extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'estimate_entry';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['product_id', 'quantity', 'utility', 'price'], 'required'],
			[['product_id', 'currency', 'variant_currency', 'product_image_id', 'rank'], 'integer'],
			[['estimate_id'], 'exist', 'targetClass' => Estimate::className(), 'targetAttribute' => 'id'],
			[['product_id'], 'exist', 'targetClass' => Product::className(), 'targetAttribute' => 'id'],
			[['quantity'], 'integer', 'min' => 1],
			[['utility', 'price', 'variant_price'], 'number', 'min' => 0],
			[['currency', 'variant_currency'], 'in', 'range' => array_keys(Currency::labels())],
			[['description'], 'safe'],
			[['checked', 'sample_delivered'], 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'estimate_id' => 'Presupuesto',
			'product_id' => 'Producto',
			'product_image_id' => 'Imágen',
			'rank' => 'Orden',
			'quantity' => 'Cantidad',
			'utility' => 'Utilidad',
			'price' => 'Costo',
			'currency' => 'Moneda',
			'variant_price' => 'Impresión',
			'variant_currency' => 'Moneda',
			'description' => 'Descripción',
			'checked' => 'Confirmado',
			'sample_delivered' => 'Muestra',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEstimate() {
		return $this->hasOne(Estimate::className(), ['id' => 'estimate_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProduct() {
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeValidate() {
		if (parent::beforeValidate()) {
			// Always use ARS for variant price
			if ($this->isNewRecord) {
				$this->variant_currency = Currency::CURRENCY_ARS;
			}
			$this->price = str_replace(',', '.', $this->price);
			$this->variant_price = str_replace(',', '.', $this->variant_price);
			$this->utility = str_replace(',', '.', $this->utility);
			$this->utility = str_replace('%', '', $this->utility);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the currency label.
	 * @return string
	 */
	public function getCurrencyLabel() {
		return Currency::labels()[$this->currency];
	}

	/**
	 * Get the variant currency label.
	 * @return string
	 */
	public function getVariantCurrencyLabel() {
		return Currency::labels()[$this->variant_currency];
	}

	/**
	 * Get price + variant_price converted to ARS
	 * @return float
	 */
	public function getCost() {
		$price = $this->price;
		if ($this->currency == Currency::CURRENCY_USD) {
			$price = $this->price * $this->estimate->us;
		}
		$variantPrice = $this->variant_price ? $this->variant_price : 0;
		if ($this->variant_currency == Currency::CURRENCY_USD) {
			$variantPrice = $this->variant_price * $this->estimate->us;
		}
		return $price + $variantPrice;
	}
	
	/**
	 * Get cost + utility margin
	 * @return float
	 */
	public function getSubtotal() {
		return $this->cost * (1 + $this->utility / 100);
	}

	/**
	 * Get quantity * cost
	 * @return float
	 */
	public function getQuantityCost() {
		return $this->cost * $this->quantity;
	}
	
	/**
	 * Get quantity * subtotal
	 * @return float
	 */
	public function getQuantitySubtotal() {
		return $this->subtotal * $this->quantity;
	}
	
	/**
	 * Get utility margin
	 * @return float
	 */
	public function getUtilityMargin() {
		return $this->quantityCost * ($this->utility / 100);
	}
}
