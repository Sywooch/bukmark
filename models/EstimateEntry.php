<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate_entry".
 *
 * @property integer $id
 * @property string $product_id
 * @property string $variant_id
 * @property string $quantity
 * @property string $utility
 * @property string $price
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
            [['product_id', 'variant_id', 'quantity', 'utility', 'price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'variant_id' => 'Variant ID',
            'quantity' => 'Quantity',
            'utility' => 'Utility',
            'price' => 'Price',
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
}
