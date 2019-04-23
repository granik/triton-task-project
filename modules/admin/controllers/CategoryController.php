<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\controllers\AppAdminController;
use app\models\EventCategory;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\CategoryForm;
use yii\filters\VerbFilter;


/**
 * Description of CategoryController
 *
 * @author Granik
 */
class CategoryController extends AppAdminController{
    
    public function actionList() {
        $dataProvider = new ActiveDataProvider([
            'query' => EventCategory::find()
                ->where(['is_deleted' => 0]),
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
//            'pages' => $pages
        ]);
    }
    
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
    
    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoryForm();
        $name = $_POST['CategoryForm']['name'];
        if( Yii::$app->request->method === 'POST' && $model->find()->where(['name' => $name])->one() !== null) {
            
            Yii::$app->session->setFlash('exists', 'Категория <strong>' . $name . '</strong> уже существует!');
            
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Категория <strong>' . $name . '</strong> добавлена!');
            return $this->redirect(['list']);
        }
            
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $eventCategory = new EventCategory();
        $name = $_POST['CategoryForm']['name'];
        $records = $eventCategory
                ->find()
                ->where('id != ' . $id)
                ->andWhere(['is_deleted' => 0, 'name' => $name])
                ->all();            

        $is_post = Yii::$app->request->method === 'POST' ? true : false;
        if($is_post && !empty($records) ) {
            //запись повторяется
            Yii::$app->session->setFlash('exists', 'Категория <strong>' . $name . '</strong> уже существует!');
        } else if( $model->load(Yii::$app->request->post()) && $model->save() ) {
            //всё ОК
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['list']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->update();

        return $this->redirect(['list']);
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoryForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
}
