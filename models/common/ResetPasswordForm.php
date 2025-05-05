<?php

namespace app\models\common;

use app\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{

    public $password;
    public $password_repeat;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token = null, $config = [])
    {

        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Токен не может быть пустым!');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidArgumentException('Неверный токен восстановления пароля!');
        }

        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают!'],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }

}