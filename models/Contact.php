<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property integer $supplier_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 *
 * @property Supplier $supplier
 */
class Contact extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'contact';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['email'], 'email'],
			[['first_name', 'last_name', 'email', 'phone'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'supplier_id' => 'Supplier ID',
			'first_name' => 'Nombre',
			'last_name' => 'Apellido',
			'email' => 'Email',
			'phone' => 'Teléfono',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSupplier() {
		return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
	}

	/**
	 * Validate that at least one field contains information.
	 * @inheritdoc
	 */
	public function beforeValidate() {
		$attributes = $this->activeAttributes();
		$valid = FALSE;
		foreach ($attributes as $attribute) {
			if ($this->$attribute) {
				$valid = TRUE;
			}
		}
		if (!$valid) {
			foreach ($attributes as $attribute) {
				$this->addError($attribute, 'Al menos un campo debe contener información.');
			}
		}
		return parent::beforeValidate();
	}

	/**
	 * Returns the full name for the contact
	 * @return string
	 */
	public function getFullName() {
		$fullName = '';
		$fullName .= $this->first_name;
		$fullName .= $this->first_name && $this->last_name ? ' ' . $this->last_name : $this->last_name;
		return $fullName;
	}
	
	/**
	 * Returns the display name for the contact
	 * @return string
	 */
	public function getDisplayName() {
		$displayName = '';
		if ($this->fullName) {
			$displayName = $this->fullName;
		} else if ($this->email) {
			$displayName = $this->email;
		} else if ($this->phone) {
			$displayName = $this->phone;
		}
		return $displayName;
	}

}
