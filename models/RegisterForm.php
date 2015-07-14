<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterForm extends Model {

	public $username;
	public $password;
	public $repeat_password;
	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules() {
		return [
			// username and password are both required
			[['username', 'password', 'repeat_password'], 'required'],
			// password is validated by validatePassword()
			['repeat_password', 'validatePassword'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'username' => 'Usuario',
			'password' => 'Contraseña',
			'repeat_password' => 'Repetir contraseña',
		];
	}
	
	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params) {
		if (!$this->hasErrors()) {
			if ($this->repeat_password != $this->password) {
				$this->addError($attribute, 'Passwords must match.');
			}
		}
	}

	/**
	 * Registers and logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function register() {
		if ($this->validate()) {
			$user = new User;
			$user->username = $this->username;
			$user->password = $this->password;
			if ($user->save()) {
				return Yii::$app->user->login($user, User::LOGIN_TIME);
			}
		} else {
			return false;
		}
	}

}
