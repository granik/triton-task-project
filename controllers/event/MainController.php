<?php

namespace app\controllers\event;

use app\controllers\AppController;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\models\event\main\Event;
use app\models\event\main\EventCategory;
use app\models\common\City;
use app\models\event\main\EventType;
use app\models\event\main\EventMainFields;
use app\models\event\main\SearchEvent;
use app\models\event\tickets\EventTicket;
use app\models\mossem\MossemFields;
use app\models\event\main\EventForm;

/**
 * Контроллер, отвечающий за события
 * 
 * Class MainController
 * @package app\controllers\event
 */
class MainController extends AppController
{
    /**
     * Инстанс модели формы редактирования/добавления
     * события
     *
     * @var EventForm
     */
    protected $event_form;
    /**
     * Инстанс модели поиска событий
     *
     * @var SearchEvent
     */
    protected $search_model;
    /**
     * MainController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventForm $event_form
     */
    public function __construct($id, $module, $config = [], EventForm $event_form, SearchEvent $search_model)
    {
        $this->event_form = $event_form;
        $this->search_model = $search_model;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница со списком актуальных событий
     * (главная страница сайта)
     * 
     * @return string
     */
    public function actionIndex()
    {
        $isArchive = false;
        $title = "Актуальные события";
        $categories = EventCategory::find()
            ->where(['is_deleted' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $searchModel = $this->search_model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $days = array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб');
        $cityList = City::findAll(['is_deleted' => 0]);

        return $this->render('index',
            compact('title',
                'categories',
                'searchModel',
                'dataProvider',
                'isArchive',
                'days',
                'cityList'
            )
        );
    }

    /**
     * Страница архива событий
     * 
     * @return string
     */
    public function actionArchive()
    {
        $isArchive = true;
        $title = 'Архив событий';
        $categories = EventCategory::find()
            ->where(['is_deleted' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $searchModel = new SearchEvent(['is_archive' => true]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $cityList = City::findAll(['is_deleted' => 0]);
        $days = array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб');

        return $this->render('index',
            compact('title',
                'categories',
                'searchModel',
                'dataProvider',
                'isArchive',
                'cityList',
                'days')
        );
    }

    /**
     * Страница события
     * 
     * @param $eventId ID события
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionShow($eventId)
    {
        $title = "Страница события";
        $where = ['event_id' => $eventId, 'is_deleted' => 0];
        $event = Event::find()
            ->with([
                'type' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'category' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'city' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'logistics' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'tickets' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'sponsors' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'services' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                },
                'finances' => function (\yii\db\ActiveQuery $query) {
                    $query->where(['is_deleted' => 0]);
                }
            ])
            ->where(['id' => $eventId, 'is_deleted' => 0])
            ->one();
        if (empty($event)) {
            throw new NotFoundHttpException("Событие не найдено!");
        }
        //если это вебинар
        if ($event->type->name === 'Вебинар') {
            return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $eventId]));
        }
        $days = array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб');
        $timestamp = strtotime($event->date);
        $event->date = $event->regularDate;
        $event->weekday = $event->date . ' (' . $days[date("w", $timestamp)] . ')';

        $mainInfo = EventMainFields::find()->with([
            'field' => function (\yii\db\ActiveQuery $query) use ($eventId) {
                $query->andWhere(['event_id' => $eventId]);
            },])
            //ToDo: переписать это нормально
            ->leftJoin('event_info', 'field_id')
            ->where(['info_fields.is_deleted' => 0])
            //чтобы если поле было удалено из админки,
            //в событиях заполненная инфа осталась
            ->orWhere("event_info.event_id = {$eventId} AND event_info.value <> '' AND event_info.field_id = info_fields.id")
            ->orderBy(['position' => SORT_ASC])
            ->all();
        $mossemData = [];
        if ($event->type->name === 'Московский семинар') {
            $mossemData = MossemFields::find()->with([
                'field' => function (\yii\db\ActiveQuery $query) use ($eventId) {
                    $query->andWhere(['mossem_id' => $eventId]);
                },])
                //ToDo: переписать это нормально
                ->leftJoin('mossem_info', 'field_id')
                ->where(['mossem_fields.is_deleted' => 0])
                //чтобы если поле было удалено из админки,
                //в событиях заполненная инфа осталась
                ->orWhere("mossem_info.mossem_id = {$eventId} AND mossem_info.value <> '' AND mossem_info.field_id = mossem_fields.id")
                ->orderBy(['position' => SORT_ASC])
                ->all();
        }

        $ticketDataProvider = new ActiveDataProvider([
            'query' => EventTicket::find()->where($where)->orderBy(['position' => SORT_ASC]),
            'sort' => false,
        ]);

        return $this->render('show',
            compact(
                'title',
                'mainInfo',
                'mossemData',
                'event',
                'ticketDataProvider'
            )
        );
    }

    /**
     * Страница с формой создания нового события
     * 
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionCreate()
    {
        $title = "Добавить событие";
        $model = $this->event_form;

        $cities = City::findAll(['is_deleted' => 0]);
        $categories = EventCategory::findAll(['is_deleted' => 0]);
        $types = EventType::findAll(['is_deleted' => 0]);

        $categoryItems = ArrayHelper::map($categories, 'id', 'name');
        $typeItems = ArrayHelper::map($types, 'id', 'name');
        $cityItems = ArrayHelper::map($cities, 'id', 'name');


        return $this->render('create',
            compact('title',
                'model',
                'cityItems',
                'categoryItems',
                'typeItems'
            )
        );
    }

    /**
     * Обработчик формы добавления события
     * 
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionStore()
    {
        $model = $this->event_form;
        if ($model->load(Yii::$app->request->post())) {
            $model->insertEvent();
        }
        return $this->redirect(Url::toRoute('event/main/index'));
    }

    /**
     * Страница с формой редактирования события
     *
     * @param $eventId ИД события
     * @return string
     */
    public function actionEdit($eventId)
    {
        $title = "Изменить событие";
        $event = Event::findOne($eventId);
        $cities = City::findAll(['is_deleted' => 0]);
        $categories = EventCategory::findAll(['is_deleted' => 0]);
        $types = EventType::findAll(['is_deleted' => 0]);
        $model = EventForm::findOne($eventId);

        $categoryItems = ArrayHelper::map($categories,'id','name');
        $typeItems = ArrayHelper::map($types, 'id', 'name');
        $cityItems = ArrayHelper::map($cities, 'id', 'name');

        return $this->render('edit',
            compact(
                'event',
                'title',
                'cityItems',
                'categoryItems',
                'typeItems',
                'model'
            )
        );
    }

    /**
     * Обработчик формы редактирования события
     *
     * @param $eventId ИД события
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($eventId)
    {
        $model = $this->event_form->findOne($eventId);
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->updateData()) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }

        }
        $model->renewUpdatedOn();
        //ВЕБИНАР
        if (Event::find()->where(['id' => $eventId])->with('type')->one()->type->name === 'Вебинар') {
            return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $eventId]));
        }

        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));

    }

    /**
     * Экшн отмены события
     *
     * @param $eventId ID события
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCancel($eventId)
    {
        $event = Event::findOne($eventId);
        $event->is_cancel = 1;
        $event->update();
//        Event::findOne($eventId)->renewUpdatedOn();

        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн возвращения события из отмененных
     *
     * @param $eventId ID события
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAbortCancel($eventId)
    {
        $event = Event::findOne($eventId);
        $event->is_cancel = 0;
        $event->update();
//        Event::findOne($eventId)->renewUpdatedOn();

        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления события
     *
     * @param $eventId ID события
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($eventId)
    {
        $event = Event::findOne($eventId);

        $event->is_deleted = 1;
        $event->update();
//        Event::findOne($eventId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(['event/main/index']));
    }

    /**
     * Экшн для получения дат, когда есть события
     * (метод API для календаря на главной странице)
     *
     * @param $year год YYYY
     * @param $month месяц 1-12
     * @throws NotFoundHttpException
     */
    public function actionAjaxCalendar($year, $month)
    { //month: 1-12
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\NotFoundHttpException("Страница не найдена");
        }
        $daysAmount = [null, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $data = Event::find()
            ->with(['type', 'category', 'city'])
            ->where(['between', 'date', "{$year}-{$month}-01", "{$year}-{$month}-{$daysAmount[$month]}"])
            ->andWhere(['event.is_deleted' => 0, 'event.is_cancel' => 0])
            ->asArray()
            ->all();
        //ToDo: переписать через response
        header("Content-Type: application/json");
        echo Json::encode($data);
        exit;
    }
}