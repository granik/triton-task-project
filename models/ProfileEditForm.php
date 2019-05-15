<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;


use Yii;
use app\models\User;
//use app\modules\admin\models\CreateUserForm;
/**
 * Description of UpdateUserForm
 *
 * @author Granik
 */
class ProfileEditForm extends User {
    //put your code here
//    
//    public $password;
//    public $password_repeat;
    public $current_password;
    private $_user;
   
    public function __construct($config = array()) {
        $this->_user = Yii::$app->user->getIdentity();
        if(!$this->_user) {
            throw new \yii\base\ErrorException("Нет активного пользователя!");
        }
        parent::__construct($config);
    }
    
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'unique', 'targetClass' => User::className(), 'filter' => ['<>', 'id', $this->_user->id],
                'message' => 'Пользователь с таким E-mail уже существует!'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 30],
//            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким E-mail уже существует! '],
            [['first_name', 'last_name'], 'trim'],
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 30],
            ['current_password', 'required'],
            ['current_password', 'string', 'min' => 6, 'max' => 20]
        ];
    }
    
    public function attributeLabels() {
        return [
            'email' => 'E-mail',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'current_password' => 'Текущий пароль'
        ];
    }
    
    public function updateProfile()
    {
 
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        
        $user = User::findOne($this->_user->id);
        
        if(!$user->validatePassword($this->current_password)) {
            $this->addError('current_password', 'Неправильный текущий пароль!');
            return false;
        }
 
        $model = new User();
        $user = $model->findOne($this->_user->id);
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        
        return $user->save() ? $user : null;
    }
}