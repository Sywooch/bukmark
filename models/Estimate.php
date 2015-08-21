<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estimate".
 *
 * @property integer $id
 * @property string $title
 * @property integer client_id
 * @property integer $user_id
 * @property integer $status
 * @property string $request_date
 * @property string $send_date 
 * @property string $total
 * @property string $cost
 * @property string $total_checked
 * @property string $cost_checked
 * @property string $us
 */
class Estimate extends \yii\db\ActiveRecord {

	const US_TO_ARS_MARGIN = 0.04;
	
	/* Statuses go here.
	  IMPORTANT: If a Status is added it must also be added
	  to statusLabels() and statusColors(). */
	const STATUS_ENTERED = 0;
	const STATUS_UTILITY = 1;
	const STATUS_PRESENTATION_PENDING = 2;
	const STATUS_WAITING_ANSWER = 3;
	const STATUS_SEND = 4;
	const STATUS_CONTACT = 5;
	const STATUS_SENT = 6;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['title', 'client_id', 'request_date'], 'required'],
			[['title'], 'string', 'max' => 255],
			[['client_id'], 'exists', 'targetClass' => Client::className(), 'targetAttribute' => 'id'],
			[['status'], 'in', 'range' => array_keys(self::statusLabels())],
			[['request_date', 'send_date'], 'date'],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'estimate';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'N° de presupuesto',
			'title' => 'Título',
			'client_id' => 'Cliente',
			'user_id' => 'Usuario',
			'status' => 'Estado',
			'request_date' => 'Solicitado',
			'send_date' => 'Enviado',
			'total' => 'Total',
			'cost' => 'Costo',
			'total_checked' => 'Total(confirmado)',
			'cost_checked' => 'Costo(confirmado)',
			'us' => 'Dólar',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClient() {
		return $this->hasOne(Client::className(), ['client_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::className(), ['user_id' => 'id']);
	}


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEntries() {
		return $this->hasMany(EstimateEntry::className(), ['estimate_id' => 'id']);
	}
	
	/**
	 * Get status labels
	 * @return string[]
	 */
	public static function statusLabels() {
		return [
			STATUS_ENTERED => 'Ingresado',
			STATUS_UTILITY => 'Utilidad',
			STATUS_PRESENTATION_PENDING => 'Presentación pendiente',
			STATUS_WAITING_ANSWER => 'Epserando respuesta',
			STATUS_SEND => 'Enviar',
			STATUS_CONTACT => 'Cotactar',
			STATUS_SENT => 'Enviado',
		];
	}
	
	/**
	 * Get status colors
	 * @return string[]
	 */
	public static function statusColors() {
		return [
			STATUS_ENTERED => 'red',
			STATUS_UTILITY => 'yellow',
			STATUS_PRESENTATION_PENDING => 'blue',
			STATUS_WAITING_ANSWER => 'orange',
			STATUS_SEND => 'green',
			STATUS_CONTACT => 'cyan',
			STATUS_SENT => 'gray',
		];
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
	public function getStatusColor() {
		return self::statusColors()[$this->status];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			$this->total = str_replace(',', '.', $this->total);
			$this->cost = str_replace(',', '.', $this->cost);
			$this->total_checked = str_replace(',', '.', $this->total_checked);
			$this->cost_checked = str_replace(',', '.', $this->cost_checked);
			$this->us = str_replace(',', '.', $this->us);
			return true;
		} else {
			return false;
		}
	}

	public function doEstimate() {
		$entries = $this->entries;
		$cost = 0;
		$total = 0;
		$cost_checked = 0;
		$total_checked = 0;
		foreach ($entries as $entry) {
			$subcost = ($entry->price + $entry->variant_price) * $entry->quantity;
			$subtotal = $subcost * (1 + $entry->utility / 100);
			$cost += $subcost;
			$total += $subtotal;
			if ($entry->checked) {
				$cost_checked += $subcost;
				$total_checked += $subtotal;
			}
		}
		$this->cost = $cost;
		$this->total = $total;
		$this->cost_checked = $cost_checked;
		$this->total_checked = $total_checked;
		$this->save();
	}

}
