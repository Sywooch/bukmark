<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "massbuy".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $quantity
 * @property string $utility_drop
 *
 * @property Product $product
 */
class Massbuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massbuy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['quantity', 'utility_drop'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['utility_drop'], 'number', 'min' => -100, 'max' => 100, 'numberPattern' => Currency::VALIDATOR_PATTERN]
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
            'quantity' => 'Cantidad',
            'utility_drop' => 'Descuento de utilidad',
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
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			$this->utility_drop = str_replace(',', '.', $this->utility_drop);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind() {
		$this->utility_drop = str_replace('.', ',', $this->utility_drop);
		parent::afterFind();
	}
}
