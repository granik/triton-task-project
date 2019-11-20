<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\common;
use app\models\User;
use Yii;
/**
 * Description of ChangePasswordForm
 *
 * @author Granik
 */
class ChangePasswordForm extends User {
    
    public $old_password;
    public $new_password;
    public $password_repeat;
    private $_user;
   
    public function __construct($config = array()) {
        $this->_user = Yii::$app->user->getIdentity();
        if(!$this->_user) {
            throw new \yii\base\ErrorException("Пользователь не найден!");
        }
        parent::__construct($config);
    }
    
    public function rules() {
        return [
                ['old_password', 'required'],
                [['new_password', 'password_repeat'], 'required'],
                [['password_repeat'], 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают!'],
                ['new_password', 'string', 'min' => 6, 'max' => 20],
            ];
    }
    
    public function attributeLabels() {
        return [
            'old_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'password_repeat' => 'Повторите пароль'
        ];
    }
    
    public function changePassword()
    {
 
        if (!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
 
        $user = User::findOne($this->_user->id);
        if(!$user->validatePassword($this->old_password)) {
            $this->addError('old_password', 'Неправильный текущий пароль!');
            return false;
        }
        $user->setPassword($this->new_password);
        return $user->save() ? $user : null;
    }
}
