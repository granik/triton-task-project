<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\common\City;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\source\SearchCity;
use app\modules\admin\models\source\CityForm;

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
        $rules = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
        
        $parentRules = parent::behaviors();
        return array_merge_recursive($rules, $parentRules);
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
        
        

        return $this->render('list', compact('dataProvider', 'searchModel'));
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
//        $name = $_POST['CityForm']['name'];
        
        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            Yii::$app->session->setFlash('success', 'Город добавлен!');
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
        if($model->load(Yii::$app->request->post() ) && $model->save()) {
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
        $model = City::findOne($id);
        $model->is_deleted = 1;
        $model->name = 'removed-' . $model->name;
        $model->save();

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
