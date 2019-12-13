<?php

namespace app\modules\admin\controllers\source;

use Yii;
use app\modules\admin\models\source\SponsorTypeForm;
use app\models\event\sponsor\SponsorType;
use app\modules\admin\models\source\SearchSponsorType;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\modules\admin\controllers\AppAdminController;
/**
 * SponsorTypeController implements the CRUD actions for SponsorType model.
 */
class SponsorTypeController extends AppAdminController
{
    /**
     * @var SearchSponsorType
     */
    protected $search;
    /**
     * @var SponsorTypeForm
     */
    protected $form;

    /**
     * SponsorTypeController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchSponsorType $search
     * @param SponsorTypeForm $form
     */
    function __construct($id, $module, $config = [], SearchSponsorType $search, SponsorTypeForm $form)
    {
        $this->search = $search;
        $this->form = $form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all SponsorType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Типы спонсоров';
        $searchModel = $this->search;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('title', 'searchModel', 'dataProvider'));
    }

    /**
     * Creates a new SponsorType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = 'Новый тип спонсора';
        $model = $this->form;

        return $this->render('create', compact('model', 'title'));
    }

    public function actionStore()
    {
        $model = $this->form;
        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            Yii::$app->session->setFlash('success', 'Тип спонсора добавлен');
            return $this->redirect(['index']);
        }
        throw new ErrorException('Ошибка обработки данных формы');
    }

    /**
     * Страница с формой редактирования
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $title = 'Править тип спонсора';
        $model = $this->findModel($id);

        return $this->render('edit', compact('title', 'model'));
    }

    /**
     * Обработчик формы редактирования
     *
     * @return \yii\web\Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['index', 'id' => $model->id]);
        }
        throw new ErrorException('Ошибка обработки данных формы');
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
        $row = SponsorType::findOne($id);
        $row->is_deleted = 1;
        $row->name = 'removed-' . $row->name;
        $row->save();

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
        if (($model = $this->form->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
