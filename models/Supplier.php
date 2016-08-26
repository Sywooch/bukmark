<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $discount
 * @property string $website
 * @property string $address
 * @property string $notes
 * @property boolean $deleted
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
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
    public function rules()
    {
        return [
            [['notes'], 'string'],
			[['name'], 'required'],
			[['discount'], 'number', 'min' => 0],
            [['name', 'website', 'address'], 'string', 'max' => 255],
			[['website'], 'url', 'defaultScheme' => 'http'],
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
			'contactFullName' => 'Contacto',
			'discount' => 'Descuento',
            'website' => 'Website',
            'address' => 'Dirección',
            'notes' => 'Comentarios',
			'contactPhone' => 'Teléfono',
			'contactEmail' => 'Email',
        ];
    }
	
	/**
	 * @inheritdoc
	 */
	public function beforeValidate() {
		if (parent::beforeValidate()) {
			$this->discount = str_replace(',', '.', $this->discount);
			$this->discount = str_replace('%', '', $this->discount);
			return true;
		} else {
			return false;
		}
	}
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['supplier_id' => 'id']);
    }
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProducts() {
		return $this->hasMany(Product::className(), ['supplier_id' => 'id']);
	}
	
	/**
     * @return string
     */
	public function getContactFullName() {
		$fullName = '';
		$contacts = $this->contacts;
		if (count($contacts) > 0) {
			$fullName = $contacts[0]->fullName;
		}
		return $fullName;
	}
	
	/**
     * @return string
     */
	public function getContactPhone() {
		$phone = "";
		foreach ($this->contacts as $contact) {
			if ($contact->phone) {
				$phone = $contact->phone;
				break;
			}
		}
		return $phone;
	}
	
	/**
     * @return string
     */
	public function getContactEmail() {
		$email = "";
		foreach ($this->contacts as $contact) {
			if ($contact->email) {
				$email = $contact->email;
				break;
			}
		}
		return $email;
	}
	
	/**
	 * Gets an id => name array.
	 * return string[]
	 */
	public static function getIdNameArray() {
		$suppliers = ArrayHelper::map(self::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		return $suppliers;
	}
}
