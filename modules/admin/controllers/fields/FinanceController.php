<?php

namespace app\modules\admin\controllers\fields;

use app\models\event\finance\FinanceFields;
use app\models\event\logistics\LogisticFields;
use app\modules\admin\models\fields\FinanceFieldsForm;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use Yii;
use app\modules\admin\controllers\AppAdminController;
/**
 * Description of FinanceFieldsController
 *
 * @author Granik
 */
class FinanceController extends AppAdminController
{
    /**
     * @var FinanceFieldsForm
     */
    public $form;

    /**
     * FinanceController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param FinanceFieldsForm $form
     */
    public function __construct($id, $module, $config = [], FinanceFieldsForm $form)
    {
        $this->form = $form;
        parent::__construct($id, $module, $config);
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
        $model = $this->form;

        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            //всё ОК
            Yii::$app->session->setFlash('success', "Поле добавлено!");
            return $this->redirect(['index']);
        }

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
        $title = "Редактирование";
        $model = $this->findModel($id);


        return $this->render('edit', [
            'model' => $model,
            'title' => $title
        ]);
    }

    /**
     * Обработчик формы редактироания
     *
     * @return \yii\web\Response
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
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $row = FinanceFields::findOne($id);
        if (!$row) {
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
     * @return FinanceFieldsForm|null the loaded model
     */
    protected function findModel($id)
    {
        if (($model = $this->form->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует');
    }
}
