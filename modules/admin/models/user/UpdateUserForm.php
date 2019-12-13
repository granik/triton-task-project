<?php

namespace app\modules\admin\models\user;

use app\models\User;

/**
 * Description of UpdateUserForm
 *
 * @author Granik
 */
class UpdateUserForm extends User
{
    //put your code here

    public $password;
    public $password_repeat;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'unique', 'targetClass' => User::className(), 'filter' => ['<>', 'id', $this->id],
                'message' => 'Пользователь с таким E-mail уже существует!'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 30],
            ['role_id', 'in', 'range' => [User::ROLE_USER, User::ROLE_ADMIN]],
//            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким E-mail уже существует! '],
            [['password', 'password_repeat'], 'safe'],
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
            'password' => 'Новый пароль',
            'password_repeat' => 'Повторите пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'role_id' => 'Роль пользователя'
        ];
    }

    public function updateUser($id)
    {

        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }

        $model = new User();
        $user = $model->findOne($id);
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        if ($id != 1) {
            $user->role_id = $this->role_id;
        }
//        $user->generateAuthKey();
        if (!empty($this->password)) {
            $user->setPassword($this->password);
        }
        return $user->save() ? $user : null;
    }
}
