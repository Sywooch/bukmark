<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property boolean $deleted
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

	public $authKey = 'authKey';
	public $accessToken;

	const LOGIN_TIME = 2592000; // login remember time in secons (2592000 = 1 month)

	/**
	 * @inheritdoc
	 */

	public static function tableName() {
		return 'user';
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
	public function rules() {
		return [
			[['username', 'password'], 'string', 'max' => 255],
			[['username'], 'unique'],
			[['username', 'password'], 'required'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'username' => 'Usuario',
			'password' => 'Contraseña'
		];
	}

	/**
	 * Gets the active user
	 * @return User
	 */
	public static function getActiveUser() {
		$userId = Yii::$app->user->id;
		return self::find()->where(['id' => $userId])->one();
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		return self::find()->where(['id' => $id])->one();
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		foreach (self::$users as $user) {
			if ($user['accessToken'] === $token) {
				return new static($user);
			}
		}

		return null;
	}

	/**
	 * Finds user by username
	 *
	 * @param  string      $username
	 * @return static|null
	 */
	public static function findByUsername($username) {
		return self::find()->where(['username' => $username])->one();
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return $this->password === $password;
	}

}
