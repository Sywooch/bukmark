<?php

namespace app\models;

use app\helpers\DateConverter;
use Yii;

/**
 * This is the model class for table "estimate".
 *
 * @property integer $id
 * @property string $title
 * @property integer $client_id
 * @property integer $client_contact_id
 * @property integer $user_id
 * @property integer $status
 * @property string $request_date
 * @property string $sent_date
 * @property string $total
 * @property string $cost
 * @property string $total_checked
 * @property string $cost_checked
 * @property string $us
 * @property boolean $deleted
 */
class Estimate extends \yii\db\ActiveRecord {
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
	 * Senarios
	 */
	const SCENARIO_GRID = 'grid';

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
	public static function find() {
		return new \app\components\DeletedQuery(get_called_class());
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['title', 'client_id', 'request_date', 'status'], 'required'],
			[['title'], 'string', 'max' => 255],
			[['client_id'], 'exist', 'targetClass' => Client::className(), 'targetAttribute' => 'id'],
			[['client_contact_id'], 'exist', 'targetClass' => ClientContact::className(), 'targetAttribute' => 'id'],
			[['status'], 'in', 'range' => array_keys(self::statusLabels())],
			[['request_date', 'sent_date'], 'date'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_GRID] = ['status'];
		return $scenarios;
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
			'title' => 'Producto solicitado',
			'client_id' => 'Cliente',
			'client_contact_id' => 'Atención',
			'user_id' => 'Usuario',
			'status' => 'Estado',
			'request_date' => 'Fecha solicitado',
			'sent_date' => 'Fecha enviado',
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
		return $this->hasOne(Client::className(), ['id' => 'client_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClientContact() {
		return $this->hasOne(ClientContact::className(), ['id' => 'client_contact_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEntries() {
		return $this->hasMany(EstimateEntry::className(), ['estimate_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReceipt() {
		return $this->hasOne(Receipt::className(), ['estimate_id' => 'id']);
	}

	/**
	 * Get status labels
	 * @return string[]
	 */
	public static function statusLabels() {
		return [
			self::STATUS_ENTERED => 'Ingresado',
			self::STATUS_UTILITY => 'Utilidad',
			self::STATUS_PRESENTATION_PENDING => 'Presentación pendiente',
			self::STATUS_WAITING_ANSWER => 'Epserando respuesta',
			self::STATUS_SEND => 'Enviar',
			self::STATUS_CONTACT => 'Contactar',
			self::STATUS_SENT => 'Enviado',
		];
	}

	/**
	 * Get status colors
	 * @return string[]
	 */
	public static function statusColors() {
		return [
			self::STATUS_ENTERED => '#ff0000',
			self::STATUS_UTILITY => '#ffff00',
			self::STATUS_PRESENTATION_PENDING => '#93c47d',
			self::STATUS_WAITING_ANSWER => '#ff9900',
			self::STATUS_SEND => '#00ff00',
			self::STATUS_CONTACT => '#666666',
			self::STATUS_SENT => '#ffffff',
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
			if ($this->isNewRecord) {
				$this->us = Currency::getUsToArs();
				$this->user_id = Yii::$app->user->id;
			}
			$this->request_date = DateConverter::convert($this->request_date);
			$this->sent_date = DateConverter::convert($this->sent_date);
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
			$subcost = $entry->quantityCost;
			$subtotal = $entry->quantitySubtotal;
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
		$this->save(false);
	}

	/**
	 * Update all sent estimates that were sent over one week ago
	 */
	public static function updateSentEstimates() {
		$date = date("Y-m-d", strtotime("-1 week"));
		$models = self::find()->active()->andWhere(['=', 'status', self::STATUS_SENT])
			->andWhere(['is not', 'sent_date', null])
			->andWhere(['<=', 'sent_date', $date])->all();
		foreach ($models as $model) {
			$model->status = self::STATUS_CONTACT;
			$model->save(false);
		}
	}

}
