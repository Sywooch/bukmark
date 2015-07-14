<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_contact".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 *
 * @property Client $client
 */
class ClientContact extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'client_contact';
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
			'client_id' => 'Client ID',
			'first_name' => 'Nombre',
			'last_name' => 'Apellido',
			'email' => 'Email',
			'phone' => 'Teléfono',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClient() {
		return $this->hasOne(Client::className(), ['id' => 'client_id']);
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
