<?php

namespace app\models;

use Yii;

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
            'address' => 'Dirección',
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
}
