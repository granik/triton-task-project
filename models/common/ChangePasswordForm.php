<?php

namespace app\models\common;

use app\models\User;
use Yii;

/**
 * Форма изменения пароля
 *
 * @author Granik
 */
class ChangePasswordForm extends User
{
    /**
     * Текущий пароль
     * @var string
     */
    public $old_password;
    /**
     * Новый пароль
     * @var string
     */
    public $new_password;
    /**
     * Повторение пароля
     * @var string
     */
    public $password_repeat;
    /**
     * Текущий пользователь
     * @var \yii\web\IdentityInterface|null
     */
    private $_user;

    /**
     * ChangePasswordForm constructor.
     * @param array $config
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     */
    public function __construct($config = array())
    {
        $this->_user = Yii::$app->user->getIdentity();
        if (!$this->_user) {
            throw new \yii\base\ErrorException("Пользователь не найден!");
        }
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['old_password', 'required'],
            [['new_password', 'password_repeat'], 'required'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают!'],
            ['new_password', 'string', 'min' => 6, 'max' => 20],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'old_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'password_repeat' => 'Повторите пароль'
        ];
    }

    /**
     * Обработчик формы изменения пароля
     *
     * @return User|bool|null
     * @throws \yii\base\ErrorException
     */
    public function changePassword()
    {

        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }

        $user = User::findOne($this->_user->id);
        if (!$user->validatePassword($this->old_password)) {
            $this->addError('old_password', 'Неправильный текущий пароль!');
            return false;
        }
        $user->setPassword($this->new_password);
        return $user->save() ? $user : null;
    }
}
