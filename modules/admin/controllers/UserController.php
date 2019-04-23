<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\admin\controllers\AppAdminController;
use app\modules\admin\models\CreateUserForm;
use app\modules\admin\models\UpdateUserForm;
use app\models\User;
use app\modules\admin\models\SearchUser;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\UserRole;
/**
 * Description of UserController
 *
 * @author Granik
 */
class UserController extends AppAdminController {
    //put your code here
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionList() {
//        die('worx!');
        $title = 'Пользователи';
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => $title,
        ]);
    }
    
    public function actionCreate() {
        $title = 'Новый пользователь';
        $model = new CreateUserForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
                return $this->redirect(['list']);
            }
        }
 
        return $this->render('create', compact('model', 'title'));
    }
    
    public function actionView($id)
    {
        $title = "Пользователи";
        $user = new User();
        $model = $user->findOne($id);
        if(empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена");
        }
        return $this->render('view', compact('model', 'title') );
    }
    
    public function actionUpdate($id) {
        $title = 'Правка пользователя';
        $user = new UpdateUserForm();
        $model = $user->findOne($id);
        $roles = UserRole::find()->asArray()->all();
        $roles = ArrayHelper::map($roles, 'id', 'name');
        if(empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена");
        }
        if($model->load( Yii::$app->request->post()) && $model->updateUser($id)) {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('update', compact('title', 'model', 'roles') );
    }
    
    public function actionDelete($id) {
        $user = User::findOne($id);
        if(empty($user->id)) {
            throw new NotFoundHttpException("Нет пользователя с таким ID");
        }
        $user->is_deleted = 1;
        $user->update();
        Yii::$app->session->setFlash('success', 'Пользователь успешно деактивирован');
        return $this->redirect(['list']);
    }
    
    public function actionRestore($id) {
        $user = User::findOne($id);
        if(empty($user->id)) {
            throw new NotFoundHttpException("Нет пользователя с таким ID");
        }
        $user->is_deleted = 0;
        $user->update();
        Yii::$app->session->setFlash('success', 'Пользователь успешно активирован');
        return $this->redirect(['list']);
    }
    
}
