<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\event\main\Event;
use app\models\event\main\EventPresenceForm;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за итоговую явку
 *
 * Class PresenceController
 * @package app\controllers\event
 */
class PresenceController extends AppController
{
    /**
     * Инстанс модели формы итоговой явки
     *
     * @var EventPresenceForm
     */
    protected $presence_form;

    /**
     * PresenceController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventPresenceForm $presence_form
     */
    public function __construct($id, $module, $config = [], EventPresenceForm $presence_form)
    {
        $this->presence_form = $presence_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница ввода данных итоговой явки
     *
     * @param $eventId ID события
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionCreate($eventId)
    {
        $event = Event::findOne($eventId);
        $title = 'Установить итоговую явку на событие';
        if (!$event->isPast) {
            //если это актуальное событие
            return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
        }
        $model = $this->presence_form;
        $modelData = Event::find()->where(['id' => $eventId])->asArray()->one();
        if (!empty($modelData)) {
            //для редактирования
            $model->load(['EventPresenceForm' => $modelData]);
        }

        return $this->render('create', compact('event', 'model', 'title'));
    }

    /**
     * Обработчик формы добавления итоговой явки
     *
     * @param $eventId ИД события
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore($eventId)
    {
        $model = $this->presence_form;
        if ($model->load(Yii::$app->request->post()) && $model->updateEventPresence($eventId)) {
            Event::findOne($eventId)->renewUpdatedOn();
            return $this->redirect(Url::toRoute(['event/main/archive']));
        }
        throw new ErrorException('Невозможно добавить данные!');
    }

    /**
     * Страница со списком итоговых явок
     *
     * @return string
     */
    public function actionIndex()
    {
        $title = 'Итоговая явка';
        //ToDo:тут избавиться от join'ов
        $dataProvider = new ActiveDataProvider([
            'query' => Event::find()
                ->innerJoin('city', 'city.id = event.city_id')
                ->select('event.id, title, date, city.name as city, presence, presence_comment')
                ->where(['event.is_deleted' => 0])
                ->andWhere("event.presence IS NOT NULL OR event.presence != ''")
                ->andWhere(['event.is_cancel' => 0])
                ->andWhere(['event.is_cancel' => 0])
                ->andWhere('event.date < CURDATE()')
                ->orderBy(['date' => SORT_ASC])
        ]);

        return $this->render('index', compact(
                'title',
                'dataProvider')
        );
    }
}