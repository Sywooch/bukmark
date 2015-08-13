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
			[['quantity'], 'unique'],
            [['utility_drop'], 'number'],
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
	public function beforeValidate() {
		if (parent::beforeValidate()) {
			$this->utility_drop = str_replace(',', '.', $this->utility_drop);
			return true;
		} else {
			return false;
		}
	}
}
