<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\SponsorTypeForm;
use app\models\SponsorType;
use app\modules\admin\models\SearchSponsorType;
use app\modules\admin\controllers\AppAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SponsorTypeController implements the CRUD actions for SponsorType model.
 */
class SponsorTypeController extends AppAdminController
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
     * Lists all SponsorType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Типы спонсоров';
        $searchModel = new searchSponsorType();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('title', 'searchModel', 'dataProvider'));
    }

    /**
     * Displays a single SponsorType model.
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
     * Creates a new SponsorType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = 'Новый тип спонсора';
        $model = new SponsorTypeForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Тип спонсора добавлен');
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', compact('model', 'title'));
    }

    /**
     * Updates an existing SponsorType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $title = 'Править тип спонсора';
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', 'Тип спонсора <strong>' . $name . '</strong> добавлен');
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', compact('title', 'model'));
    }

    /**
     * Deletes an existing SponsorType model.
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
     * Finds the SponsorType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SponsorType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SponsorTypeForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
