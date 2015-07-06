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
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'phone' => 'Phone',
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
				$this->addError($attribute, 'At least one field should contain information.');
			}
		}
		return parent::beforeValidate();
	}

	/**
	 * Get the display name for the contact
	 * @return string
	 */
	public function getDisplayName() {
		$displayName = '';
		if ($this->first_name || $this->last_name) {
			$displayName .= $this->first_name;
			$displayName .= $this->first_name ? ' ' . $this->last_name : $this->last_name;
		} else if ($this->email) {
			$displayName = $this->email;
		} else if ($this->phone) {
			$displayName = $this->phone;
		}
		return $displayName;
	}

}
