<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\event\main\Event;
use app\models\event\tickets\EventTicket;
use app\models\event\tickets\EventTicketForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Контроллер, отвечающий за электронные билеты
 * (на странице события)
 *
 * Class TicketController
 * @package app\controllers\event
 */
class TicketController extends AppController
{
    /**
     * Инстанс модели формы добавления
     * электронного билета
     *
     * @var EventTicketForm
     */
    protected $ticket_form;

    /**
     * TicketController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param EventTicketForm $ticket_form
     */
    public function __construct($id, $module, $config = [], EventTicketForm $ticket_form)
    {
        $this->ticket_form = $ticket_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница с формой добавления билета
     *
     * @param $eventId Id события
     * @return string|\yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionCreate($eventId)
    {
        $title = "Добавить билет";
        $model = $this->ticket_form;
        $event = Event::findOne(['id' => $eventId]);
        $nextPosition = $this->getNextTicketPosition($eventId);
        return $this->render('create',
            compact(
                'title',
                'event',
                'model',
                'nextPosition'
            )
        );
    }

    /**
     * Обработчик формы добавления билета
     *
     * @param $eventId
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function actionStore($eventId)
    {
        $model = $this->ticket_form;
        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'ticket_file');
            if (empty($files)) {
                return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
            }

            if (!$model->uploadFiles($files, $eventId)) {
                throw new \yii\base\ErrorException("Невозможно загрузить файлы!");
            }
            Event::findOne($eventId)->renewUpdatedOn();

        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления электронного билета
     *
     * @param $ticketId
     * @param $eventId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($ticketId, $eventId)
    {
        $ticket = EventTicket::findOne($ticketId);
        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $eventId . '/' . $ticket->filename);
        $ticket->is_deleted = 1;
        $ticket->update();
        Event::findOne($eventId)->renewUpdatedOn();
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    public function actionUp($id)
    {
        $currentRow = EventTicket::findOne(['id' => $id, 'is_deleted' => 0]);
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position - 1 === 0 ? 1 : $currentRow->position - 1;
        $rowA = EventTicket::findOne(['event_id' => $currentRow->event_id, 'position' => $newPos, 'is_deleted' => 0]);
        while (empty($rowA)) {
            $newPos--;
            $rowA = EventTicket::findOne(['event_id' => $currentRow->event_id, 'position' => $newPos, 'is_deleted' => 0]);
        }

        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }

    public function actionDown($id)
    {
        $currentRow = EventTicket::findOne(['id' => $id, 'is_deleted' => 0]);
        $maxPos = EventTicket::find()->where(['event_id' => $currentRow->event_id, 'is_deleted' => 0])->max('position');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position === $maxPos ?
            $currentRow->position :
            $currentRow->position + 1;

        $rowA = EventTicket::findOne(['event_id' => $currentRow->event_id, 'position' => $newPos, 'is_deleted' => 0]);
        while (empty($rowA)) {
            $newPos++;
            $rowA = EventTicket::findOne(['event_id' => $currentRow->event_id, 'position' => $newPos, 'is_deleted' => 0]);
        }
        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }


    public function actionSetPositions()
    {
        $events = Event::findAll(['is_deleted' => 0]);
        $initValue = 1;
        foreach($events as $event){
            $tickets = EventTicket::findAll(['event_id' => $event->id, 'is_deleted' => 0]);
            foreach($tickets as $ticket) {
                $tick = EventTicket::findOne($ticket->id);
                $tick->position = $initValue++;
                $tick->update();
            }
            $initValue = 1;
        }
        die('success');
    }

    private function getNextTicketPosition($eventId)
    {
        $ticketsOfEvent =  EventTicket::find()
            ->where(['event_id' => $eventId, 'is_deleted' => 0]);

        return $ticketsOfEvent->max('position') + 1;
    }
}