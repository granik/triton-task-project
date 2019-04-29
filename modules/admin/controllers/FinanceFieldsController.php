<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\AppAdminController;
use app\models\FinanceFields;
use app\modules\admin\models\FinanceFieldsForm;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use Yii;
/**
 * Description of FinanceFieldsController
 *
 * @author Granik
 */
class FinanceFieldsController extends AppAdminController {
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
     * Lists all LogisticFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "Финансовое инфо: поля";
        $dataProvider = new ActiveDataProvider([
            'query' => FinanceFields::find()
                ->where(['is_deleted' => 0]),
        ]);

        return $this->render('index', compact(
                'title', 
                'dataProvider')
        );
    }

    /**
     * Creates a new LogisticFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Новое поле: финансы";
        $model = new FinanceFieldsForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Поле добавлено!");
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
        $row = FinanceFields::findOne($id);
        if(!$row) {
            throw new \yii\web\NotFoundHttpException("Страница не найдена!");
        }
        $row->is_deleted = 1;
        $row->name = 'removed-' . $row->name;
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
        if (($model = FinanceFieldsForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует');
    }
}
