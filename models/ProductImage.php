<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $filename
 * @property boolean $deleted
 *
 * @property EstimateEntry[] $estimateEntries
 * @property Product $product
 */
class ProductImage extends \yii\db\ActiveRecord {

	/**
	 * @var UploadedFile
	 */
	public $imageFile;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'product_image';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			\app\components\NoDeleteBehavior::className(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return new \app\components\DeletedQuery(get_called_class());
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['product_id', 'filename'], 'required'],
			[['product_id', 'deleted'], 'integer'],
			[['filename'], 'string', 'max' => 255],
			// Accept images up to 500KB
			[['imageFile'], 'image', 'extensions' => ['jpg', 'jpeg', 'png'], 'maxSize' => 500 * 1024],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'product_id' => 'Product ID',
			'filename' => 'Filename',
			'deleted' => 'Deleted',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEstimateEntries() {
		return $this->hasMany(EstimateEntry::className(), ['product_image_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProduct() {
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}

	public function getImageUrl() {
		$imageUrl = null;
		if ($this->image) {
			$imageUrl = Yii::getAlias('@web/images/product/' . $this->image);
		}
		return $imageUrl;
	}

	/**
	 * Save the uploaded image.
	 * @return boolean true whether the file is saved successfully
	 */
	public function uploadImage() {
		$this->imageFile = UploadedFile::getInstance($this, 'imageFile');
		if ($this->imageFile) {
			$ext = end((explode(".", $this->imageFile->name)));
			$filename = uniqid() . '.' . $ext;
			echo $filename;
			$this->image = $filename;
			return $this->imageFile->saveAs(self::getImageFolder() . DIRECTORY_SEPARATOR . $filename);
		} else {
			return FALSE;
		}
	}

}
