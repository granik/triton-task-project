<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use app\models\common\ProfileEditForm;
use Yii;
use app\models\common\ChangePasswordForm;
/**
 * Description of ProfileController
 *
 * @author Granik
 */
class ProfileController extends AppController {
    
    protected $user;
    
    public function __construct($id, $module, $config = array()) {
        $this->user = Yii::$app->user->getIdentity();
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex() {
        $title = "Профиль";
        $user = $this->user;
        return $this->render('profile', compact('title', 'user'));
    }
    
    public function actionEdit() {
        $title = "Редактировать профиль";
        $model = ProfileEditForm::findOne($this->user->id);
        
        if($model->load( Yii::$app->request->post() ) && $model->updateProfile()) {
            Yii::$app->session->setFlash('success', 'Данные профиля сохранены!');
            return $this->redirect(['index']);
        }
        return $this->render('edit', compact('title', 'model'));
    }
    
    public function actionChangePasswd() {
        $title = "Редактировать профиль";
        $model = ChangePasswordForm::findOne($this->user->id);
        
        if($model->load( Yii::$app->request->post() ) && $model->changePassword()) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('success', 'Пароль профиля изменен!');
            return $this->redirect(['site/login']);
        }
        return $this->render('change_passwd', compact('title', 'model'));
        
    }
}
