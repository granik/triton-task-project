<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\LogisticFields;
use app\modules\admin\models\LogisticForm;
use app\modules\admin\models\SearchLogisticFields;
use app\modules\admin\controllers\AppAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LogisticFieldsController implements the CRUD actions for LogisticFields model.
 */
class LogisticFieldsController extends AppAdminController
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
     * Lists all LogisticFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "Логистическое инфо: поля";
        $searchModel = new SearchLogisticFields();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact(
                'title',
                'searchModel', 
                'dataProvider')
        );
    }

    /**
     * Displays a single LogisticFields model.
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
     * Creates a new LogisticFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Новое поле: логистика";
        $model = new LogisticForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Поле <strong>{$name}</strong> добавлено!");
            return $this->redirect(['index']);
        }

        

        return $this->render('create', compact('model', 'title'));
    }

    /**
     * Updates an existing LogisticFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Данные обновлены!");
            return $this->redirect(['index']);
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LogisticFields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row = $this->findModel($id);
        $row->is_deleted = 1;
        $row->update();
        return $this->redirect(['index']);
    }

    /**
     * Finds the LogisticFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LogisticFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogisticForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует');
    }
}
