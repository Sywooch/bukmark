<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "variant".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $description
 * @property string $price
 * @property integer $currency
 *
 * @property Product $product
 */
class Variant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['description', 'price', 'currency'], 'required'],
            [['product_id', 'currency'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Producto',
            'description' => 'Descripción',
            'price' => 'Precio',
            'currency' => 'Moneda',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
	
	/**
	 * @inheritdoc
	 */
	public function beforeValidate() {
		if (parent::beforeValidate()) {
			$this->price = str_replace(',', '.', $this->price);
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
}
