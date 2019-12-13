<?php

namespace app\controllers\webinar;

use app\controllers\AppController;
use app\models\webinar\WebinarField;
use Yii;
use app\components\Functions;
use app\models\event\main\Event;
use app\models\event\main\EventCategory;
use app\models\common\City;
use app\models\event\main\EventType;
use app\models\event\main\EventForm;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за вебинары
 *
 * @author Granik
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
     * MainController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventForm $event_form
     */
    public function __construct($id, $module, $config = [], EventForm $event_form)
    {
        $this->event_form = $event_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница вебинара
     * 
     * @param $webinarId
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($webinarId)
    {
        $title = "Страница вебинара";
        $webinar = Event::find()
            ->with(['sponsors' => function (ActiveQuery $query) {
                $query->andWhere(['is_deleted' => 0]);
            }])
            ->where(['id' => $webinarId, 'is_deleted' => 0])
            ->one();
        if (empty($webinar)) {
            throw new \yii\web\NotFoundHttpException("Страница не найдена");
        }
        $days = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        $timestamp = strtotime($webinar['date']);
        $webinar['date'] = Functions::toSovietDate($webinar['date']);
        $webinar->weekday = $webinar['date'] . ' (' . $days[date("w", $timestamp)] . ')';
        //ToDo: переписать это нормально(если получится)
        $mainInfo = WebinarField::find()->with([
            'field' => function (\yii\db\ActiveQuery $query) use ($webinarId) {
                $query->andWhere(['webinar_id' => $webinarId]);
            },])
            //ToDo: переписать это нормально
            ->leftJoin('webinar_info', 'field_id')
            ->where(['webinar_fields.is_deleted' => 0])
            //чтобы если поле было удалено из админки,
            //в событиях заполненная инфа осталась
            ->orWhere("webinar_info.webinar_id = {$webinarId} AND webinar_info.value <> '' AND webinar_info.field_id = webinar_fields.id")
            ->orderBy(['position' => SORT_ASC])
            ->all();

        return $this->render('show',
            compact(
                'title',
                'id',
                'mainInfo',
                'webinar'
            )
        );
    }

    /**
     * Страница редактирования вебинара
     * 
     * @param $webinarId
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionEdit($webinarId)
    {
        $title = "Изменить вебинар";
        $event = Event::findOne($webinarId);
        $city = City::findAll(['is_deleted' => 0]);
        $categories = EventCategory::findAll(['is_deleted' => 0]);
        $types = EventType::findAll(['is_deleted' => 0]);
        return $this->render('edit',
            compact(
                'event',
                'title',
                'city',
                'categories',
                'types'
            )
        );
    }

    /**
     * Обработчик формы редактирования вебинара
     *
     * @param $webinarId
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($webinarId)
    {
        $model = $this->event_form;
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->updateData()) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            Event::findOne($webinarId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
    }

    /**
     * Экшн отмены вебинара
     *
     * @param $webinarId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCancel($webinarId)
    {
        $event = Event::findOne($webinarId);

        $event->is_cancel = 1;
        $event->update();
        return $this->redirect(Url::toRoute(['webinar/main/how', 'webinarId' => $webinarId]));
    }

    /**
     * Экшн возвращения вебинара из отмененных
     *
     * @param $webinarId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAbortCancel($webinarId)
    {
        $event = Event::findOne($webinarId);

        $event->is_cancel = 0;
        $event->update();
        return $this->redirect(Url::toRoute(['webinar/main/show', 'webinarId' => $webinarId]));
    }
}
