<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property integer $id
 * @property integer $estimate_id
 * @property integer $status
 * @property string $created_date
 * @property integer $type
 * @property string $iva
 *
 * @property Estimate $estimate
 */
class Receipt extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['status', 'type', 'estimate_id'], 'required'],
			[['estimate_id'], 'unique'],
			[['estimate_id'], 'exist', 'targetClass' => Estimate::className(), 'targetAttribute' => 'id'],
			[['status'], 'in', 'range' => array_keys(self::statusLabels())],
			[['type'], 'in', 'range' => array_keys(self::typeLabels())],
		];
	}

	const IVA = 21;

	/* Statuses go here.
	  IMPORTANT: If a Status is added it must also be added
	  to statusLabels() and statusColors(). */
	const STATUS_PENDING = 0;
	const STATUS_CANCELED = 1;
	const STATUS_CHARGED = 2;

	/* types go here.
	  IMPORTANT: If a type is added it must also be added
	  to typeLabels() */
	const TYPE_A = 0;
	const TYPE_B = 1;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'receipt';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'estimate_id' => 'Presupuesto',
			'status' => 'Estado',
			'created_date' => 'Fecha',
			'type' => 'Tipo',
			'iva' => 'IVA',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEstimate() {
		return $this->hasOne(Estimate::className(), ['id' => 'estimate_id']);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				$this->created_date = date('Y-m-d');
				$this->iva = self::IVA;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get status labels
	 * @return string[]
	 */
	public static function statusLabels() {
		return [
			self::STATUS_PENDING => 'Pendiente',
			self::STATUS_CANCELED => 'Cancelado',
			self::STATUS_CHARGED => 'Cobrado',
		];
	}

	/**
	 * Get status colors
	 * @return string[]
	 */
	public static function statusColors() {
		return [
			self::STATUS_PENDING => 'red',
			self::STATUS_CANCELED => 'darkgray',
			self::STATUS_CHARGED => 'gray',
		];
	}

	/**
	 * Get type labels
	 * @return string[]
	 */
	public static function typeLabels() {
		return [
			self::TYPE_A => 'A',
			self::TYPE_B => 'B',
		];
	}

	/**
	 * 
	 * @return float
	 */
	public function getGross() {
		return $this->estimate->total_checked * (1 + $this->iva / 100);
	}

	/**
	 * 
	 * @return float
	 */
	public function getIVATotal() {
		return $this->estimate->total_checked * ($this->iva / 100);
	}

	/**
	 * 
	 * @return string
	 */
	public function getStatusLabel() {
		return self::statusLabels()[$this->status];
	}

	/**
	 * 
	 * @return string
	 */
	public function getTypeLabel() {
		return self::typeLabels()[$this->type];
	}

	/**
	 * Retunrs a brief list of the checked products
	 * @return string
	 */
	public function getProducts() {
		$entries = $this->estimate->getEntries()->where(['checked' => true])->all();
		// Group the entries by product
		$groupedEntries = [];
		foreach ($entries as $entry) {
			if(!isset($groupedEntries[$entry->product->id])) {
				$groupedEntries[$entry->product->id] = [];
				$groupedEntries[$entry->product->id]['product'] = $entry->product;
				$groupedEntries[$entry->product->id]['quantity'] = $entry->quantity;
			} else {
				$groupedEntries[$entry->product->id]['quantity'] += $entry->quantity;
			}
		}
		// Get the string
		$string = '';
		foreach ($groupedEntries as $entry) {
			$explodeArray = explode(" ", $entry['product']->title);
			$title = count($explodeArray) ? $explodeArray[0] : "";
			$quantity = $entry['quantity'];
			$string .= $title . " ($quantity), ";
		}
		// Remove last ", "
		$string = trim($string, ", ");
		return $string;
	}

}
