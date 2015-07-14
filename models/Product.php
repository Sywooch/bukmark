<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $supplier_id
 * @property string $supplier_code
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
	 * @var UploadedFile
	 */
	public $imageFile;

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
			['category_id', 'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'],
			['supplier_id', 'exist', 'targetClass' => Supplier::className(), 'targetAttribute' => 'id'],
			[['description'], 'string'],
			[['price'], 'number'],
			[['supplier_code', 'bukmark_code'], 'string', 'max' => 255],
			[['supplier_code'], 'unique', 'when' => function ($model) {
			return self::findOne(['supplier_id' => $model->supplier_id, 'supplier_code' => $model->supplier_code]) ? TRUE : FALSE;
		}],
			[['bukmark_code'], 'unique'],
			// Accept images up to 500KB
			[['imageFile'], 'image', 'extensions' => ['jpg', 'jpeg', 'png'], 'maxSize' => 500 * 1024],
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
			'supplier_code' => 'Código de proveedor',
			'bukmark_code' => 'Código interno',
			'image' => 'Imagen',
			'description' => 'Descripción',
			'price' => 'Precio',
			'currency' => 'Moneda',
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

	/**
	 * Get the folder where the product images are stored.
	 * @return string
	 */
	public static function getImageFolder() {
		return Yii::getAlias('@images/product');
	}

	/**
	 * Save the uploaded image.
	 * @return boolean true whether the file is saved successfully
	 */
	public function uploadImage() {
		if ($this->imageFile) {
			$this->imageFile = UploadedFile::getInstance($this, 'imageFile');
			$ext = end((explode(".", $this->imageFile->name)));
			$filename = uniqid() . '.' . $ext;
			$this->image = $filename;
			return $this->imageFile->saveAs(self::getImageFolder() . DIRECTORY_SEPARATOR . $filename);
		} else {
			return FALSE;
		}
	}

}
