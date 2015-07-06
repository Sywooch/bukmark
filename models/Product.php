<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $supplier_id
 * @property string $provider_code
 * @property string $bukmark_code
 * @property string $image
 * @property string $description
 * @property string $price
 * @property integer $currency
 *
 * @property Category $category
 * @property Supplier $supplier
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['category_id', 'supplier_id', 'description'], 'required'],
            [['category_id', 'supplier_id', 'currency'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['provider_code', 'bukmark_code', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'supplier_id' => 'Supplier ID',
            'provider_code' => 'Provider Code',
            'bukmark_code' => 'Bukmark Code',
            'image' => 'Image',
            'description' => 'Description',
            'price' => 'Price',
            'currency' => 'Currency',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }
}
