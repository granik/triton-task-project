<?php

namespace app\modules\admin\controllers\source;

use Yii;
use app\models\event\main\EventType;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\source\EventTypeForm;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\AppAdminController;
/**
 * Контроллер, отвечающий за типы событий
 * в админке
 *
 * @author Granik
 */
class EventTypeController extends AppAdminController
{
    /**
     * @var EventTypeForm
     */
    public $event_type_form;

    /**
     * EventTypeController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventTypeForm $eventTypeForm
     */
    public function __construct($id, $module, $config = [], EventTypeForm $eventTypeForm)
    {
        $this->event_type_form = $eventTypeForm;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EventType::find()
                ->where(['is_deleted' => 0]),
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Страница с формой создания нового типа
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->render('create', [
            'model' => $this->event_type_form,
        ]);
    }

    /**
     * Обработчик формы создания типа
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore()
    {
        $model = $this->event_type_form;
        if ($model->load(Yii::$app->request->post()) && $model->createNew()) {
            Yii::$app->session->setFlash('success', 'Тип события добавлен!');
            return $this->redirect(['list']);
        }
        throw new ErrorException('Ошибка обработки данных формы!');
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
        return $this->render('edit', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Обработчик формы редактирования
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //всё ОК
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['list']);
        }
        throw new ErrorException('Ошибка обработки данных формы!');
    }

    /**
     * Удаляет тип события
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = EventType::findOne($id);
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
        if (($model = $this->event_type_form->findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Страница не найдена.');
    }
}
