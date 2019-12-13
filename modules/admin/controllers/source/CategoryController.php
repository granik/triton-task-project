<?php

namespace app\modules\admin\controllers\source;

use Yii;
use app\models\event\main\EventCategory;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\source\CategoryForm;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\AppAdminController;

/**
 * Контроллер, отвечающий за управление
 * категориями событий в админке
 *
 * @author Granik
 */
class CategoryController extends AppAdminController
{
    /**
     * Инстанс модели формы добавления/редактирования
     * категории
     *
     * @var CategoryForm
     */
    protected $category_form;

    /**
     * CategoryController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param CategoryForm $categoryForm
     */
    public function __construct($id, $module, $config = [],  CategoryForm $categoryForm)
    {
        $this->category_form = $categoryForm;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница со списком категорий
     *
     * @return string
     */
    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EventCategory::find()
                ->where(['is_deleted' => 0]),
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Страница создания категории
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->category_form;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обработчик формы создания категории
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore()
    {
        $model = $this->category_form;
        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            Yii::$app->session->setFlash('success', 'Категория добавлена!');
            return $this->redirect(['list']);
        }
        throw new ErrorException("Невозможно обработать форму!");
    }

    /**
     * Страница с формой редактирования категории
     *
     * @param $id
     * @return string
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Обработчик формы редактирования категории
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['list']);
        }
    }

    /**
     * Экшн удаления категории
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = EventCategory::findOne($id);
        $model->name = 'removed-' . $model->name;
        $model->is_deleted = 1;
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
        if (($model = $this->category_form->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
