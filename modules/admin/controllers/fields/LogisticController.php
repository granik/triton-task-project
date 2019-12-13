<?php

namespace app\modules\admin\controllers\fields;

use Yii;
use app\models\event\logistics\LogisticFields;
use app\modules\admin\models\fields\LogisticForm;
use app\modules\admin\models\fields\SearchLogisticFields;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\AppAdminController;

/**
 * LogisticFieldsController implements the CRUD actions for LogisticFields model.
 */
class LogisticController extends AppAdminController
{
    /**
     * @var LogisticForm
     */
    protected $form;
    /**
     * @var SearchLogisticFields
     */
    protected $searchModel;

    /**
     * LogisticController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param LogisticForm $form
     */
    function __construct($id, $module, $config = [], LogisticForm $form, SearchLogisticFields $searchModel)
    {
        $this->form = $form;
        $this->searchModel = $searchModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all LogisticFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "Логистическое инфо: поля";
        $searchModel = $this->searchModel;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact(
                'title',
                'searchModel',
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
        $title = "Новое поле: логистика";
        $model = $this->form;
        return $this->render('create', compact('model', 'title'));
    }

    /**
     * Обработчик формы добавления
     *
     * @return \yii\web\Response
     */
    public function actionStore()
    {
        $model = $this->form;

        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Поле добавлено!");
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing LogisticFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Обработчик формы редактирования
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Данные обновлены!");
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing LogisticFields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $row = LogisticFields::findOne($id);
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
        if (($model = LogisticForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует');
    }
}
