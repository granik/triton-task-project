<?php

namespace app\modules\admin\controllers\source;

use Yii;
use app\models\common\City;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\source\SearchCity;
use app\modules\admin\models\source\CityForm;
use app\modules\admin\controllers\AppAdminController;
/**
 * AdminController implements the CRUD actions for City model.
 */
class CityController extends AppAdminController
{
    /**
     * @var SearchCity
     */
    protected $search_model;
    /**
     * @var CityForm
     */
    protected $city_form;

    /**
     * CityController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchCity $searchModel
     * @param CityForm $cityForm
     */
    public function __construct($id, $module, $config = [], SearchCity $searchModel, CityForm $cityForm)
    {
        $this->search_model = $searchModel;
        $this->city_form = $cityForm;
        parent::__construct($id, $module, $config);
    }


    /**
     * Страница со списком городов
     *
     * @return mixed
     */
    public function actionList()
    {
//        $pages = new Pagination(['defaultPageSize' => 10]);
        $dataProvider = $this->search_model->search(Yii::$app->request->queryParams);
        $searchModel = $this->search_model;

        return $this->render('list', compact('dataProvider', 'searchModel'));
    }


    /**
     * Страница с формой создания города
     *
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->render('create', [
            'model' =>$this->city_form,
        ]);
    }

    /**
     * Обработчик формы создания города
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore()
    {
        $model = $this->city_form;

        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            Yii::$app->session->setFlash('success', 'Город добавлен!');
            return $this->redirect(['list']);
        }
        throw new ErrorException('Ошибка обработки формы');
    }

    /**
     * Страница с формой редактирования города
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {

        return $this->render('edit', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Обработчик формы редактирования города
     *
     * @param $id
     * @return \yii\web\Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['list']);
        }
        throw new ErrorException('Ошибка обработки данных формы');
    }

    /**
     * Удаление города
     *
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
        if (($model = $this->city_form->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена!');
    }
}
