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
class Product extends \yii\db\ActiveRecord {
	/* Currency types go here.
	  IMPORTANT: If a currency is added it must also be added
	  to currencyLabels(). */

	const CURRENCY_ARS = 0;
	const CURRENCY_USD = 1;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'product';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['category_id', 'supplier_id', 'description'], 'required'],
			[['category_id', 'supplier_id', 'currency'], 'integer'],
			[['description'], 'string'],
			[['price'], 'number'],
			[['provider_code', 'bukmark_code', 'image'], 'string', 'max' => 255],
			[['provider_code'], 'unique', 'when' => function ($model) {
				return self::findOne(['supplier_id' => $model->supplier_id, 'supplier_code' => $model->supplier_code]) ? TRUE : FALSE;
			}],
			[['bukmark_code'], 'unique'],
			// Accept images up to 500KB
			[['image'], 'image', 'extension' => ['jpg', 'jpeg', 'png'], 'maxSize' => 500 * 1024],
			[['currency'], 'in', 'range' => array_keys(self::currencyLabels())],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
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
	 * Get currency labels
	 * @return string[]
	 */
	public static function currencyLabels() {
		return [
			self::CURRENCY_ARS => '$',
			self::CURRENCY_USD => 'US$',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory() {
		return $this->hasOne(Category::className(), ['id' => 'category_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSupplier() {
		return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
	}

}
