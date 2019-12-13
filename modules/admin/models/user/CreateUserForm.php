<?php

namespace app\modules\admin\models\user;

use yii\base\Model;
use app\models\User;

/**
 * Description of CreateUserForm
 *
 * @author Granik
 */
class CreateUserForm extends Model
{
    //put your code here

    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $first_name;
    public $last_name;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 30],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким E-mail уже существует!',
                'filter' => ['=', 'is_deleted', 0]
            ],
            [['password', 'password_repeat'], 'required'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают!'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            [['first_name', 'last_name'], 'trim'],
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
        ];
    }

    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
}
