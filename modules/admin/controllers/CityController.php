<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\City;
use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\AppAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\SearchCity;
use app\modules\admin\models\CityForm;

/**
 * AdminController implements the CRUD actions for City model.
 */
class CityController extends AppAdminController
{
    /**
     * {@inheritdoc}
     */
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
     * Lists all City models.
     * @return mixed
     */
    public function actionList()
    {
//        $pages = new Pagination(['defaultPageSize' => 10]);
        $searchModel = new SearchCity();;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
//            'pages' => $pages
        ]);
    }

    /**
     * Displays a single City model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CityForm();
        $name = $_POST['CityForm']['name'];
        if( Yii::$app->request->method === 'POST' && $model->find()->where(['is_deleted' => 0, 'name' => $name])->one() !== null) {
            
            Yii::$app->session->setFlash('exists', 'Город <strong>' . $name . '</strong> уже существует!');
            
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Город <strong>' . $name . '</strong> добавлен!');
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
        $city = new City();
        $name = $_POST['CityForm']['name'];
        $records = $city
                ->find()
                ->where('id != ' . $id)
                ->andWhere(['is_deleted' => 0, 'name' => $name])
                ->all();
        $is_post = Yii::$app->request->method === 'POST' ? true : false;
        
        if($is_post && !empty($records) ) {
            //имя повторяется
            Yii::$app->session->setFlash('exists', 'Город <strong>' . $name . '</strong> уже существует!');
        } else if($model->load(Yii::$app->request->post() ) && $model->save()) {
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
        if (($model = CityForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена!');
    }
}
