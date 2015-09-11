<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string $name
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
            'website' => 'Website',
            'address' => 'Dirección',
            'notes' => 'Comentarios',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['supplier_id' => 'id']);
    }
}
