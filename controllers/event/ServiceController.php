<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\common\City;
use app\models\event\main\Event;
use app\models\event\services\EventService;
use app\models\event\services\EventServiceForm;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за дополнительные услуги
 * (на странице события)
 *
 * Class ServiceController
 * @package app\controllers\event
 */
class ServiceController extends AppController
{
    /**
     * Инстанс модели формы добавления
     * дополнительных услуг
     *
     * @var EventServiceForm
     */
    protected $service_form;
    /**
     * ServiceController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventServiceForm $service_form
     */
    public function __construct($id, $module, $config = [], EventServiceForm $service_form)
    {
        $this->service_form = $service_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой добавления
     *
     * @param $eventId ID события
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreate($eventId)
    {
        $title = "Добавить доп. услугу";
        $model = $this->service_form;
        $event = Event::findOne($eventId);
        $cities = City::findAll(['is_deleted' => 0]);
        $cityItems = ArrayHelper::map($cities, 'id', 'name');

        if (!$event) {
            throw new \yii\web\NotFoundHttpException("Нет события с таким ID");
        }
        return $this->render('add',
            compact(
                'title',
                'event',
                'model',
                'cityItems'
            )
        );

    }

    public function actionStore($eventId)
    {
        $model = $this->service_form;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Event::findOne($eventId)->renewUpdatedOn();
            return $this->redirect(['event/main/show', 'eventId' => $eventId]);
        }

        throw new ErrorException('Не удалось обработать данные!');
    }

    /**
     * Страница с формой редактирования доп. услуг
     *
     * @param $eventId ID события
     * @param $serviceId ID строки в таблице доп. услуг
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionEdit($eventId, $serviceId)
    {
        $title = "Править доп. услугу";
        $model = EventServiceForm::findOne($serviceId);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Нет доп. услуги с таким ID");
        }
        $event = Event::findOne($eventId);
        if (!$event) {
            throw new \yii\web\NotFoundHttpException("Нет события с таким ID");
        }
        $cities = City::find()
            ->where(['is_deleted' => 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        $cityItems = ArrayHelper::map($cities, 'id', 'name');

        return $this->render('edit',
            compact(
                'title',
                'event',
                'model',
                'cityItems'
            )
        );

    }

    /**
     * Обработчик формы редактирования доп. услуги
     *
     * @param $eventId ID события
     * @param $serviceId ID доп. услуги
     * @return \yii\web\Response
     */
    public function actionUpdate($eventId, $serviceId)
    {
        $model = EventServiceForm::findOne($serviceId);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Event::findOne($eventId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления доп. услуги
     *
     * @param $eventId ID события
     * @param $serviceId ID доп. услуги
     * @return \yii\web\Response
     */
    public function actionDelete($eventId, $serviceId)
    {
        $service = EventService::findOne([
            'id' => $serviceId,
            'event_id' => $eventId
        ]);
        $service->is_deleted = 1;
        $service->save();
        Event::findOne($eventId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }
}