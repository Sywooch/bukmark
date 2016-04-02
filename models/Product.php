<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $supplier_id
 * @property string $title
 * @property string $supplier_code
 * @property string $bukmark_code
 * @property string $description
 * @property boolean $deleted
 *
 * @property Category $category
 * @property Supplier $supplier
 */
class Product extends \yii\db\ActiveRecord {
	
	const GALLERY_IMAGE_TYPE = 'product';
	const GALLERY_IMAGE_BEHAVIOR = 'galleryBehavior';

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
			self::GALLERY_IMAGE_BEHAVIOR => [
				'class' => \zxbodya\yii2\galleryManager\GalleryBehavior::className(),
				'tableName' => '{{%product_image}}',
				'type' => self::GALLERY_IMAGE_TYPE,
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@webroot/images/product/'),
				'url' => Yii::getAlias('@web/images/product/'),
				'versions' => [
					'small' => function ($img) {
						/** @var \Imagine\Image\ImageInterface $img */
						return $img->copy()->thumbnail(new \Imagine\Image\Box(200, 200));
					},
					'medium' => function ($img) {
						/** @var Imagine\Image\ImageInterface $img */
						$dstSize = $img->getSize();
						$maxWidth = 800;
						if ($dstSize->getWidth() > $maxWidth) {
							$dstSize = $dstSize->widen($maxWidth);
						}
						return $img->copy()->resize($dstSize);
					},
				]
			],
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

	/**
	 * Returns an array that could be used to populate a product dropdown selector.
	 * @return string[] each entry is formed as bukmark_code - title and the array is
	 * indexed by id.
	 */
	public static function getDropdownData() {
		$productsArray = self::find()->active()->asArray()->all();
		$dropdownData = ArrayHelper::map($productsArray, 'id', function ($productArray) {
					return $productArray['bukmark_code'] . ' - ' . $productArray['title'];
				});
		return $dropdownData;
	}

}
