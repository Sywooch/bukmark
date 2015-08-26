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
 * @property string $title
 * @property string $supplier_code
 * @property string $bukmark_code
 * @property string $image
 * @property string $description
 * @property boolean $deleted
 *
 * @property Category $category
 * @property Supplier $supplier
 */
class Product extends \yii\db\ActiveRecord {

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
	public function behaviors() {
		return [
			\app\components\NoDeleteBehavior::className(),
		];
	}
	
	/**
     * @inheritdoc
     */
	public static function find()
    {
        return new \app\components\DeletedQuery(get_called_class());
    }
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['category_id', 'supplier_id', 'title'], 'required'],
			[['category_id', 'supplier_id'], 'integer'],
			['category_id', 'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'],
			['supplier_id', 'exist', 'targetClass' => Supplier::className(), 'targetAttribute' => 'id'],
			[['description'], 'string'],
			[['title', 'supplier_code', 'bukmark_code'], 'string', 'max' => 255],
			[['supplier_code'], 'unique', 'when' => function ($model) {
			return self::findOne(['supplier_id' => $model->supplier_id, 'supplier_code' => $model->supplier_code]) ? TRUE : FALSE;
		}],
			[['bukmark_code'], 'unique'],
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
			'category_id' => 'Categoría',
			'supplier_id' => 'Proveedor',
			'title' => 'Título',
			'supplier_code' => 'Código de proveedor',
			'bukmark_code' => 'Código interno',
			'image' => 'Imagen',
			'description' => 'Descripción',
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

	public function getImageUrl() {
		$imageUrl = null;
		if ($this->image) {
			$imageUrl = '@web/images/product/' . $this->image;
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
