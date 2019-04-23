<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\controllers\AppAdminController;
use app\models\EventType;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\EventTypeForm;
use yii\filters\VerbFilter;

/**
 * Description of EventTypeController
 *
 * @author Granik
 */
class EventTypeController extends AppAdminController {
    
    public function actionList() {
        $dataProvider = new ActiveDataProvider([
            'query' => EventType::find()
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
        $model = new EventTypeForm();
        $name = $_POST['EventTypeForm']['name'];
        if( Yii::$app->request->method === 'POST' && $model->find()->where(['name' => $name, 'is_deleted' => 0])
                ->one() !== null) {
            
            Yii::$app->session->setFlash('exists', 'Тип события <strong>' . $name . '</strong> уже существует!');
            
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Тип события <strong>' . $name . '</strong> добавлен!');
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
        $eventType = new EventType();
        $name = $_POST['EventTypeForm']['name'];
        $records = $eventType
                ->find()
                ->where('id != ' . $id)
                ->andWhere(['is_deleted' => 0, 'name' => $name])
                ->all();
        
        $is_post = Yii::$app->request->method === 'POST' ? true : false;
        
        if($is_post && !empty($records) ) {
            //запись повторяется
            Yii::$app->session->setFlash('exists', 'Тип события <strong>' . $name . '</strong> уже существует!');
            
        } else if( $model->load( Yii::$app->request->post() ) && $model->save() ) {
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
        if (($model = EventTypeForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
