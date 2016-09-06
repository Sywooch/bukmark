<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $name
 * @property string $cuit
 * @property string $address
 * @property string $delivery_address
 * @property string $payment_conditions
 * @property string $notes
 * @property boolean $deleted
 */
class Client extends \yii\db\ActiveRecord
{
	const CONTACT_DROPDOWN_PLACEHOLDER = 'Elegir contacto';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
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
    public function rules()
    {
        return [
			[['name'], 'required'],
            [['notes'], 'string'],
            [['name', 'cuit', 'address', 'delivery_address', 'payment_conditions'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'cuit' => 'CUIT',
            'address' => 'Dirección fiscal',
			'delivery_address' => 'Dirección de entrega',
            'payment_conditions' => 'Condiciones de pago',
            'notes' => 'Notas',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(ClientContact::className(), ['client_id' => 'id']);
    }
	
	/**
	 * @return array
	 */
	public function getContactsArray() {
		$contacts = [];
		foreach ($this->contacts as $contact) {
			$contacts[$contact->id] = $contact->displayName;
		}
		natcasesort($contacts);
		return $contacts;
	}
	
	/**
	 * @return array
	 */
	public static function getDefaultContactsArray() {
		return [null => self::CONTACT_DROPDOWN_PLACEHOLDER];
	}
	
	/**
	 * Gets an id => name array.
	 * return string[]
	 */
	public static function getIdNameArray() {
		$clients = ArrayHelper::map(self::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		return $clients;
	}
}
