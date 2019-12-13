<?php

namespace app\controllers\event;

use Yii;
use app\controllers\AppController;
use app\models\event\finance\FinanceFields;
use app\models\event\finance\FinanceForm;
use app\models\event\finance\FinanceInfo;
use app\models\event\main\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за финансовую информацию
 * (на странице события)
 *
 * Class FinanceController
 * @package app\controllers\event
 */
class FinanceController extends AppController
{
    /**
     * Инстанс модели формы добавления/редактирования
     * финансовой информации
     *
     * @var FinanceForm
     */
    protected $finance_form;
    /**
     * FinanceController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [], FinanceForm $finance_form)
    {
        $this->finance_form = $finance_form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница добавления финансовой информации
     *
     * @param $eventId ID события
     * @return string|\yii\web\Response
     */
    public function actionCreate($eventId)
    {
        $title = "Добавить финансовую информацию";

        $event = Event::findOne($eventId);
        $form = $this->finance_form;

        $fields = FinanceFields::findAll(['is_deleted' => 0]);
        $fields = ArrayHelper::map($fields, 'id', 'name');

        return $this->render('add',
            compact(
                'title',
                'form',
                'fields',
                'event'
            )
        );
    }

    /**
     * Обработчик формы добавления финансовой информации
     * 
     * @param $eventId
     * @return \yii\web\Response
     */
    public function actionStore($eventId)
    {
        $form = $this->finance_form;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            Event::findOne($eventId)->renewUpdatedOn();
            return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
        }
    }

    /**
     * Страница редактирования финансовой информации
     *
     * @param $eventId ИД события
     * @param $itemId ИД строки в таблице финансового инфо
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    public function actionEdit($eventId, $itemId)
    {
        $title = "Править финансовую информацию";
        $event = Event::findOne($eventId);

        $form = $this->finance_form;
        $where = ['id' => $itemId, 'is_deleted' => 0];

        $fields = FinanceFields::findAll(['is_deleted' => 0]);
        $fields = ArrayHelper::map($fields, 'id', 'name');
        $model = FinanceForm::findOne($where);

        return $this->render('edit',
            compact(
                'title',
                'form',
                'fields',
                'event',
                'model'
            )
        );
    }

    /**
     * Обработчик формы редактирования финансовой информации
     * 
     * @param $eventId
     * @param $itemId
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($eventId, $itemId)
    {
        $form = $this->finance_form;
        if ($form->load(Yii::$app->request->post())) {

            if (!$form->updateData($itemId)) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            Event::findOne($eventId)->renewUpdatedOn();
        }
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }

    /**
     * Экшн удаления финансовой информации
     *
     * @param $eventId ИД события
     * @param $itemId ИД строки в таблице финансового инфо
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($eventId, $itemId)
    {
        /* delete handler begin */
        if (Yii::$app->request->post('remove')) {
            $currentRow = FinanceInfo::findOne(['id' => $itemId]);
            $currentRow->is_deleted = 1;
            $currentRow->update();
            Event::findOne($eventId)->renewUpdatedOn();
        }
        /* delete handler end*/
        return $this->redirect(Url::toRoute(['event/main/show', 'eventId' => $eventId]));
    }
}